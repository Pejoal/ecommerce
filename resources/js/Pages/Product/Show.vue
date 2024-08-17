<script setup>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
  product: {
    type: Object,
    default: {},
  },
});
</script>
<template>
  <Head :title="props.product.title" />

  <AuthLayout>
    <p>pro details</p>
    {{ product }}
  </AuthLayout>
</template>
