<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { Head } from "@inertiajs/vue3";
import ReuseableModal from "@/Components/ReuseableModal.vue";
import axios from "axios";

const props = defineProps({
  categories: {
    type: Array,
    default: [],
  },
});

let showModal = ref(false);

const form = useForm({
  id: null,
  name: "",
  active: 0,
});

const massForm = useForm({
  selectedCategories: [],
});

const toggleSelection = (categoryId) => {
  const index = massForm.selectedCategories.indexOf(categoryId);
  if (index > -1) {
    massForm.selectedCategories.splice(index, 1);
  } else {
    massForm.selectedCategories.push(categoryId);
  }
};

const isSelected = (categoryId) =>
  massForm.selectedCategories.includes(categoryId);

const deleteCategory = (categoryId) => {
  if (confirm("Are you sure you want to delete this category?")) {
    form.delete(route("admin.categories.destroy", categoryId), {
      onSuccess: () => {
        massForm.selectedCategories = massForm.selectedCategories.filter(
          (id) => id !== categoryId
        );
      },
    });
  }
};

const massDeleteCategorys = () => {
  if (confirm("Are you sure you want to delete the selected categories?")) {
    massForm.delete(route("admin.categories.massDestroy"), {
      onSuccess: () => {
        massForm.selectedCategories.value = [];
      },
    });
  }
};

const storeCategory = () => {
  form.post(route("admin.categories.store"), {
    onSuccess: () => {
      showModal.value = false;
      form.reset();
    },
    onError: (errors) => console.error("Form errors:", errors),
  });
};

const updateCategory = () => {
  form.patch(route("admin.categories.update", form.id), {
    onSuccess: () => {
      showModal.value = false;
      form.reset();
    },
    onError: (errors) => console.error("Form errors:", errors),
  });
};

const editCategory = (categoryId) => {
  axios
    .get(route("admin.categories.edit", categoryId))
    .then((response) => {
      form.id = response.data.id;
      form.name = response.data.name;
      form.active = response.data.active;

      showModal.value = true;
    })
    .catch((error) => console.error(error));
};

const addCategory = () => {
  showModal.value = true;
  form.reset();
};
</script>

<template>
  <Head :title="'Categorys Dashboard'" />

  <AuthLayout>
    <div class="my-2 flex items-center justify-center space-x-2">
      <button class="btn btn-primary" @click="addCategory">Add Category</button>
      <button
        @click="massDeleteCategorys"
        class="btn btn-danger"
        :disabled="massForm.selectedCategories.length === 0"
      >
        Delete Selected
      </button>
    </div>

    <!-- Create/Edit Category Modal -->
    <ReuseableModal
      :classes="['w-[90%] md:w-[80%] lg:w-[60%] h-full']"
      :header="form.id ? 'Edit Category' : 'Create Category'"
      :show="showModal"
      @close="showModal = false"
    >
      <template #content>
        <form @submit.prevent="storeCategory">
          <section class="space-y-4">
            <!-- Name -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700"
                >Name</label
              >
              <input
                id="name"
                v-model="form.name"
                type="text"
                placeholder="Enter name"
                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              />
              <p v-if="form.errors.name" class="text-red-600 text-sm">
                {{ form.errors.name }}
              </p>
            </div>

            <!-- Active -->
            <div class="flex items-center">
              <input
                id="active"
                v-model="form.active"
                :checked="form.active"
                type="checkbox"
                class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <label for="active" class="ml-2 text-sm text-gray-700"
                >Active</label
              >
            </div>

            <div class="flex justify-end">
              <button
                v-if="form.id"
                form="updateCategory"
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                :disabled="form.processing"
              >
                Update Category
              </button>
              <button
                v-else
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                :disabled="form.processing"
              >
                Create Category
              </button>
            </div>
          </section>
        </form>
        <form
          @submit.prevent="updateCategory"
          id="updateCategory"
          class="hidden"
        ></form>
      </template>
    </ReuseableModal>

    <!-- Category Table -->
    <div class="lg:flex items-center justify-center overflow-x-auto w-full">
      <table class="w-full bg-white border border-gray-200 rounded-lg">
        <thead>
          <tr class="bg-gray-100">
            <th class="w-16 p-1 text-left">
              <input
                type="checkbox"
                @click="
                  (e) =>
                    (massForm.selectedCategories = e.target.checked
                      ? props.categories.map((p) => p.id)
                      : [])
                "
                class="form-checkbox"
              />
            </th>
            <th class="p-1 text-left font-semibold">ID</th>
            <th class="p-1 text-left font-semibold">Name</th>
            <th class="p-1 text-left font-semibold">Active</th>

            <th class="p-1 text-left font-semibold">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="category in props.categories"
            :key="category.id"
            class="border-t hover:bg-gray-50"
          >
            <td class="p-1">
              <input
                type="checkbox"
                :value="category.id"
                @change="toggleSelection(category.id)"
                :checked="isSelected(category.id)"
                class="form-checkbox"
              />
            </td>
            <td class="p-1">{{ category.id }}</td>
            <td class="p-1">{{ category.name }}</td>
            <td class="p-1 font-bold text-green-600" v-if="category.active">
              Yes
            </td>
            <td class="p-1 font-bold text-red-600" v-else>No</td>
            <td class="p-1 flex space-x-2">
              <button
                @click="editCategory(category.id)"
                class="btn btn-success"
              >
                Edit
              </button>
              <button
                @click="deleteCategory(category.id)"
                class="btn btn-danger"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AuthLayout>
</template>

<style scoped></style>
