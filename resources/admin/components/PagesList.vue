<template>
  <div class="p-d-flex p-flex-wrap">
    <div v-if="pages.length < 1" class="p-as-center p-js-center">
      No pages to display.
    </div>
    <PagesListItem
      v-else
      v-for="page in pages"
      :page="page"
      :key="page.ID"
      @remove="() => remove(page.ID)"
    ></PagesListItem>
  </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, PropType } from "@vue/runtime-core";
import { Page } from "../@types";
import PagesListItem from "./PagesListItem.vue";

export default defineComponent({
  components: { PagesListItem },
  props: {
    pages: Array as PropType<Page[]>,
  },
  emits: ["remove"],
  setup(props, { emit }) {
    const remove = (index) => {
      emit("remove", index);
    };
    return { remove };
  },
});
</script>
