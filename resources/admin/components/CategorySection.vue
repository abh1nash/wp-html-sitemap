<template>
  <Card class="p-mt-2 p-mb-4">
    <template #title>
      <CategorySectionTitle
        @update="updateCategory"
        @delete="deleteCategory"
        @move="(position) => $emit('move', position)"
        :deletable="!category.isDefault"
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
  props: { category: {} as PropType<Category> },
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
    const displayablePages = computed(() => {
      let display = ref<Page[]>([]);

      display.value = pagesList.value.filter((page) => {
        let show: boolean = true;
        if (props.category.contain == "selected") {
          show = false;
        }
        if (
          props.category.contain == "children" &&
          page.post_parent != props.category.parent
        ) {
          show = false;
        }
        if (props.category.includes.includes(page.ID)) {
          show = true;
        }
        if (props.category.excludes.includes(page.ID)) {
          show = false;
        }
        return show;
      });

      //   // Eliminate all pages except children if applicable
      //   if (props.category.contain == "children") {
      //     display.value.forEach((page, index) => {
      //       if (page.post_parent != props.category.parent) {
      //         display.value.splice(index, 1);
      //       }
      //     });
      //   }

      //   // Include pages the category wants to include
      //   const idMap = ref<number[]>(display.value.map((page) => page.ID));
      //   props.category.includes.forEach((pageId) => {
      //     if (!idMap.value.includes(pageId)) {
      //       display.value.push(
      //         pagesList.value.find((page) => page.ID == pageId)
      //       )[0];
      //     }
      //   });

      //   // Remove pages the category wants to exclude
      //   display.value.forEach((page, index) => {
      //     if (props.category.excludes.includes(page.ID)) {
      //       display.value.splice(index, 1);
      //     }
      //   });

      return display.value.sort((a, b) => b.menu_order - a.menu_order);
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
