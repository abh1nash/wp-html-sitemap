<template>
  <Card class="p-mr-2" style="background-color: var(--gray-100);">
    <template #content>
      <div class="p-d-flex">
        <div class="p-mr-2">
          <div class="p-text-bold">
            {{ page.post_title }}
          </div>
          <div class="p-text-italic">
            <small>Page ID: {{ page.ID }}</small>
          </div>
        </div>
        <div>
          <Button
            @click="$emit('remove', page.ID)"
            class="p-button-danger p-button-sm p-button-outlined"
          >
            <i class="dashicons dashicons-no-alt"></i>
          </Button>
        </div>
      </div>
    </template>
  </Card>
</template>
<script lang="ts">
import { defineComponent, PropType } from "@vue/runtime-core";
import Card from "primevue/card";
import Button from "primevue/button";
import { Page } from "../@types";
export default defineComponent({
  components: { Card, Button },
  emits: ["remove"],
  props: { page: {} as PropType<Page> },
});
</script>
