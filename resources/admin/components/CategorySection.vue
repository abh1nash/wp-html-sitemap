<template>
  <Card class="p-mt-2 p-mb-4">
    <template #title>
      <CategorySectionTitle
        @update="updateCategory"
        @delete="deleteCategory"
        @move="(position) => $emit('move', position)"
        :deletable="!category.isDefault"
        :parent="category.parent"
        :name="category.name"
        :url="category.url"
        :contain="category.contain"
      />
    </template>
    <template #content>
      <div v-if="includablePages.length > 0" class="p-mb-4">
        <form @submit.prevent="addToInclude">
          <div class="p-d-flex">
            <div class="p-input-group">
              <Dropdown
                :options="includablePages"
                option-label="post_title"
                option-value="ID"
                v-model="newInclude"
                class="p-mr-2"
              ></Dropdown>
              <Button
                type="submit"
                class="p-button-success p-button-sm p-button-outlined"
                >Add Page</Button
              >
            </div>
          </div>
        </form>
      </div>
      <PagesList @remove="exclude" :pages="displayablePages"></PagesList>
    </template>
  </Card>
</template>
<script lang="ts">
import {
  defineComponent,
  PropType,
  Ref,
  inject,
  ref,
  onMounted,
} from "@vue/runtime-core";
import Card from "primevue/card";
import Button from "primevue/button";

import Dropdown from "primevue/dropdown";
import { computed } from "vue";
import { Category, Page } from "../@types";
import CategorySectionTitle from "./CategorySectionTitle.vue";
import PagesList from "./PagesList.vue";

export default defineComponent({
  props: {
    category: {} as PropType<Category>,
    index: Number as PropType<number>,
  },
  components: { Card, CategorySectionTitle, PagesList, Dropdown, Button },
  emits: ["updateCategory", "deleteCategory", "exclude", "include", "move"],
  setup(props, { emit }) {
    const updateCategory = (data) => {
      emit("updateCategory", data);
    };
    const deleteCategory = () => {
      emit("deleteCategory");
    };
    const pagesList: Ref<Page[]> = inject("pagesList");
    const categoriesList: Ref<Category[]> = inject("categoriesList");

    const exclude = (pageId) => {
      emit("exclude", pageId);
    };
    const include = (pageId) => {
      emit("include", pageId);
    };
    const newInclude = ref<number>();
    const addToInclude = () => {
      include(newInclude.value);
    };

    const pagesForCategory = (categoryIndex: number) => {
      let pages = ref<Page[]>([]);

      pages.value = pagesList.value.filter((page) => {
        let show: boolean = true;
        if (categoriesList.value[categoryIndex].contain == "selected") {
          show = false;
        }
        if (
          categoriesList.value[categoryIndex].contain == "children" &&
          page.post_parent != categoriesList.value[categoryIndex].parent
        ) {
          show = false;
        }
        if (categoriesList.value[categoryIndex].includes.includes(page.ID)) {
          show = true;
        }
        if (categoriesList.value[categoryIndex].excludes.includes(page.ID)) {
          show = false;
        }
        return show;
      });
      return pages;
    };

    const displayablePages = computed(() => {
      let displayPages = pagesForCategory(props.index).value;
      if (props.category.contain == "unlisted") {
        let categoriesPagesMap = categoriesList.value.map(
          (category, index) => ({
            pages: pagesForCategory(index).value,
            contain: category.contain,
          })
        );
        categoriesPagesMap.forEach((category, index) => {
          if (index != props.index && category.contain != "unlisted") {
            displayPages.forEach((page, j) => {
              if (category.pages.indexOf(page) >= 0) {
                displayPages.splice(j, 1);
              }
            });
          }
        });
      }
      return displayPages.sort((a, b) => b.menu_order - a.menu_order);
    });

    const includablePages = computed(() => {
      let includable = ref<Page[]>([]);
      [...pagesList.value].forEach((page) => {
        if (!displayablePages.value.includes(page)) includable.value.push(page);
      });
      return includable.value;
    });

    return {
      newInclude,
      addToInclude,
      updateCategory,
      deleteCategory,
      displayablePages,
      includablePages,
      exclude,
      include,
    };
  },
});
</script>
