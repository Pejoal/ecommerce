<script setup>
import { Head } from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";

const props = defineProps({
  orders: {
    type: Array,
    default: [],
  },
});
</script>

<template>
  <Head title="Cart" />

  <AuthLayout>
    <main class="p-2">
      <h1 class="text-2xl font-bold pb-2">Your Orders</h1>
    </main>
  </AuthLayout>
</template>
