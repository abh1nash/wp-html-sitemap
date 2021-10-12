<template>
  <div class="p-d-flex p-ai-center">
    <div v-if="!editMode" class="p-mr-2 p-d-flex p-ai-center">
      <div class="p-mr-2">
        {{ name ? name : "(no name specified)" }}
        <i v-if="url" class="dashicons dashicons-external"></i>
        <div v-if="url">
          <div class="p-text-sm" style="font-size: 0.8em;">
            <small
              ><a :href="url" target="_blank">{{ url }}</a></small
            >
          </div>
        </div>
      </div>
      <div class="p-mr-5">
        <button @click="editMode = true" class="button button-secondary">
          Edit
        </button>
      </div>
      <div>
        <Button
          @click="$emit('move', -1)"
          title="Move Up"
          icon="dashicons dashicons-arrow-up-alt"
          class="p-button-text p-button-secondary p-button-sm p-button-rounded"
        >
        </Button>
        <Button
          @click="$emit('move', 1)"
          title="Move Down"
          icon="dashicons dashicons-arrow-down-alt"
          class="p-button-text p-button-secondary p-button-sm p-button-rounded"
        >
        </Button>
      </div>
    </div>
    <div v-else>
      <form @submit="updateCategory">
        <div class="p-inputgroup">
          <InputText v-model="editName" placeholder="Category Name" />
        </div>
        <div class="p-inputgroup p-mt-2">
          <!-- <span><i class="dashicons dashicons-admin-site"></i></span> -->
          <InputText placeholder="Category URL" type="text" v-model="editUrl" />
        </div>
        <div class="p-d-flex p-mt-2">
          <Dropdown
            :options="containableOptions"
            :disabled="!deletable"
            option-label="name"
            option-value="value"
            v-model="editContain"
            class="p-mr-2"
          />
          <Dropdown
            v-if="editContain == 'children'"
            :options="parentOptions"
            option-label="name"
            option-value="value"
            v-model="editParent"
          />
        </div>
        <Button
          type="submit"
          @click="updateCategory"
          class="p-button-success p-mt-2 p-mr-2"
        >
          Update</Button
        >
        <Button
          @click="editMode = false"
          class="p-button-danger p-button-outlined p-mt-2"
        >
          Cancel</Button
        >
      </form>
    </div>
    <div class="p-ml-auto">
      <Button
        v-if="deletable"
        @click="deleteCategory"
        class="p-button-danger p-button-outlined p-button-sm"
        >Delete Category</Button
      >
    </div>
  </div>
  <div
    class="p-divider p-component p-divider-horizontal p-divider-solid p-divider-left"
  ></div>
</template>
<script lang="ts">
import {
  defineComponent,
  PropType,
  ref,
  inject,
  computed,
  onMounted,
  Ref,
} from "@vue/runtime-core";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Divider from "primevue/divider";
import Dropdown from "primevue/dropdown";

import { CategoryContainable, Page } from "../@types";

const containableOptions = [
  { name: "All", value: "unlisted" },
  { name: "Children of", value: "children" },
  { name: "Selected", value: "selected" },
];

export default defineComponent({
  components: { Button, InputText, Divider, Dropdown },
  props: {
    name: String as PropType<string>,
    url: String as PropType<string>,
    contain: String as PropType<CategoryContainable>,
    parent: Number as PropType<number>,
    deletable: Boolean as PropType<boolean>,
  },
  emits: ["delete", "update", "move"],
  setup(props, { emit }) {
    const editMode = ref<Boolean>(false);
    const editName = ref<string>(props.name);
    const editUrl = ref<string>(props.url);
    const editContain = ref<string>(props.contain);
    const editParent = ref<number>(props.parent);

    const updateCategory = () => {
      emit("update", {
        name: editName.value,
        url: editUrl.value,
        contain: editContain.value,
        parent: editParent.value,
      });
      editMode.value = false;
    };

    const deleteCategory = () => {
      emit("delete");
    };

    const pagesList: Ref<Page[]> = inject("pagesList");
    const parentOptions = computed(() =>
      pagesList.value.map((page) => ({ name: page.post_title, value: page.ID }))
    );
    return {
      editMode,
      editName,
      editUrl,
      editContain,
      editParent,
      updateCategory,
      deleteCategory,
      containableOptions,
      parentOptions,
    };
  },
});
</script>

<style scoped>
.p-divider.p-divider-horizontal:before {
  border-top: 1px solid #dee2e6;
}
</style>
