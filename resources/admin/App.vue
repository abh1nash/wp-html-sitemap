<template>
  <div v-if="isLoading" class="p-card p-mt-2">
    <div class="p-card-content p-text-center p-py-2">
      <ProgressSpinner />
    </div>
  </div>
  <div v-else>
    <Toast position="bottom-right" group="br" />
    <div class="p-d-flex">
      <button
        :disabled="saving"
        @click="saveData"
        class="button button-primary p-mr-2 p-d-flex p-ai-center"
      >
        <ProgressSpinner style="width: 20px; height: 20px;" v-if="saving" />
        <span> Save Data</span>
      </button>
      <button @click="addCategory" class="button button-secondary">
        Add Category
      </button>
    </div>
    <CategorySection
      v-for="(category, index) in categoriesList"
      :key="index"
      :category="category"
      @update-category="(data) => updateCategory(index, data)"
      @delete-category="() => deleteCategory(index)"
      @exclude="(pageId) => excludePage(index, pageId)"
      @include="(pageId) => includePage(index, pageId)"
      @move="(steps) => moveCategory(index, steps)"
    />
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, provide } from "@vue/runtime-core";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import ProgressSpinner from "primevue/progressspinner";
import CategorySection from "./components/CategorySection.vue";
import { PluginAPIResponse, Page, Category } from "./@types";
export default defineComponent({
  components: { ProgressSpinner, CategorySection, Toast },
  setup() {
    let isLoading = ref<Boolean>(true);
    let pagesList = ref<Page[]>([]);
    let categoriesList = ref<Category[]>([]);

    const toast = useToast();

    provide("pagesList", pagesList);
    provide("categoriesList", categoriesList);

    const headers = { "X-WP-Nonce": nonce };

    fetch("/wp-json/ak-sitemap/v1/page-data", { headers })
      .then((response) => response.json())
      .then((data: PluginAPIResponse) => {
        pagesList.value = data.pages;
        categoriesList.value = data.options;
        isLoading.value = false;
      });

    const saving = ref<boolean>(false);

    const moveCategory = (index: number, steps: number) => {
      if (index + steps > categoriesList.value.length - 1 || index + steps < 0)
        return;
      let temp = categoriesList.value[index];
      categoriesList.value[index] = categoriesList.value[index + steps];
      categoriesList.value[index + steps] = temp;
    };

    const saveData = () => {
      saving.value = true;
      fetch("/wp-json/ak-sitemap/v1/page-data", {
        method: "POST",
        headers,
        body: JSON.stringify(categoriesList.value),
      })
        .then((response) => {
          saving.value = false;
          console.log("done");
          toast.add({
            severity: "success",
            group: "br",
            summary: "Data saved.",
            detail:
              "You changes have been successfully saved. You can now view the sitemap with your changes.",
            life: 5000,
          });
        })
        .catch((err) => {
          toast.add({
            severity: "danger",
            group: "br",
            summary: "Failed to save.",
            detail: err,
            life: 5000,
          });
        });
    };

    const addCategory = () => {
      categoriesList.value.push({
        name: "",
        contain: "unlisted",
        url: "",
        parent: 0,
        includes: [],
        excludes: [],
        isDefault: false,
      });
    };

    const updateCategory = (index: number, data: Category) => {
      for (let key in data) {
        categoriesList.value[index][key] = data[key];
      }
    };

    const deleteCategory = (index) => {
      categoriesList.value.splice(index, 1);
    };

    const excludePage = (index, pageId) => {
      if (!pageId) return;
      if (categoriesList.value[index].includes.includes(pageId)) {
        categoriesList.value[index].includes.splice(
          categoriesList.value[index].includes.indexOf(pageId),
          1
        );
        return;
      }
      if (!categoriesList.value[index].excludes.includes(pageId))
        categoriesList.value[index].excludes.push(pageId);
    };

    const includePage = (index, pageId) => {
      if (!pageId) return;
      if (categoriesList.value[index].excludes.includes(pageId)) {
        categoriesList.value[index].excludes.splice(
          categoriesList.value[index].excludes.indexOf(pageId),
          1
        );
        return;
      }
      if (!categoriesList.value[index].includes.includes(pageId))
        categoriesList.value[index].includes.push(pageId);
    };

    return {
      isLoading,
      categoriesList,
      saving,
      addCategory,
      updateCategory,
      moveCategory,
      deleteCategory,
      excludePage,
      includePage,
      saveData,
    };
  },
});
</script>
