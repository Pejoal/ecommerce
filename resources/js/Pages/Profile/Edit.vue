<script setup>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head, useForm } from "@inertiajs/vue3";

defineProps({
  mustVerifyEmail: Boolean,
  status: String,
  addresses: {
    type: Array,
    default: [],
  },
});

const form = useForm({
  profile_photo: null,
});

const uploadProfilePhoto = () => {
  form.post(route("profile.photo.update"), {
    preserveScroll: true,
  });
};

const addressForm = useForm({
  type: "",
  address1: "",
  address2: "",
  city: "",
  state: "",
  zipcode: "",
  country_code: "",
});

const addAddress = () => {
  addressForm.post(route("user.address.store"), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      addressForm.reset();
    },
  });
};

const deleteAddress = (id) => {
  addressForm.delete(route("user.address.destroy", id)).then(() => {});
};
</script>

<template>
  <Head :title="trans('words.profile')" />

  <AuthLayout>
    <div class="bg-gray-50 pb-2">
      <div class="p-2 sm:p-4 shadow sm:rounded-lg">
        <UpdateProfileInformationForm
          :must-verify-email="mustVerifyEmail"
          :status="status"
          class="max-w-xl"
        />
      </div>

      <div class="p-2 sm:p-4 shadow sm:rounded-lg">
        <UpdatePasswordForm class="max-w-xl" />
      </div>

      <form
        class="p-2 sm:p-4 shadow sm:rounded-lg"
        @submit.prevent="uploadProfilePhoto"
      >
        <section class="flex justify-between flex-col sm:flex-row">
          <div class="my-2">
            <label class="pr-2" for="profile_photo">
              {{ trans("words.profile_photo") }}
            </label>
            <input
              id="profile_photo"
              type="file"
              @input="form.profile_photo = $event.target.files[0]"
            />
          </div>
          <button
            class="btn btn-success"
            type="submit"
            :disabled="form.processing"
          >
            {{ trans("words.upload") }}
          </button>
        </section>
        <p
          v-if="form.errors.profile_photo"
          class="text-sm bg-red-600 rounded-md my-1 px-2 py-1"
        >
          {{ form.errors.profile_photo }}
        </p>
        <progress
          v-if="form.progress"
          :value="form.progress.percentage"
          max="100"
        >
          {{ form.progress.percentage }}%
        </progress>
        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.recentlySuccessful" class="text-sm">
            {{ trans("words.uploaded") }}
          </p>
        </Transition>
      </form>
      <!-- Address Management -->
      <div class="p-2 sm:p-4 shadow sm:rounded-lg">
        <h2 class="text-xl font-semibold mb-4">Addresses</h2>

        <!-- Add Address Form -->
        <form @submit.prevent="addAddress" class="mb-4">
          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <label for="type">Address Type</label>
              <input
                v-model="addressForm.type"
                type="text"
                id="type"
                class="w-full border border-gray-300 rounded-md p-2"
                required
              />
              <Transition
                enter-from-class="opacity-0"
                leave-to-class="opacity-0"
                class="transition ease-in-out"
              >
                <p v-if="addressForm.errors.type" class="text-sm text-red-600">
                  {{ addressForm.errors.type }}
                </p>
              </Transition>
            </div>

            <div>
              <label for="address1">Address 1</label>
              <input
                v-model="addressForm.address1"
                type="text"
                id="address1"
                class="w-full border border-gray-300 rounded-md p-2"
                required
              />
              <Transition
                enter-from-class="opacity-0"
                leave-to-class="opacity-0"
                class="transition ease-in-out"
              >
                <p
                  v-if="addressForm.errors.address1"
                  class="text-sm text-red-600"
                >
                  {{ addressForm.errors.address1 }}
                </p>
              </Transition>
            </div>

            <div>
              <label for="address2">Address 2</label>
              <input
                v-model="addressForm.address2"
                type="text"
                id="address2"
                class="w-full border border-gray-300 rounded-md p-2"
              />
              <Transition
                enter-from-class="opacity-0"
                leave-to-class="opacity-0"
                class="transition ease-in-out"
              >
                <p
                  v-if="addressForm.errors.address2"
                  class="text-sm text-red-600"
                >
                  {{ addressForm.errors.address2 }}
                </p>
              </Transition>
            </div>

            <div>
              <label for="city">City</label>
              <input
                v-model="addressForm.city"
                type="text"
                id="city"
                class="w-full border border-gray-300 rounded-md p-2"
                required
              />
              <Transition
                enter-from-class="opacity-0"
                leave-to-class="opacity-0"
                class="transition ease-in-out"
              >
                <p v-if="addressForm.errors.city" class="text-sm text-red-600">
                  {{ addressForm.errors.city }}
                </p>
              </Transition>
            </div>

            <div>
              <label for="state">State</label>
              <input
                v-model="addressForm.state"
                type="text"
                id="state"
                class="w-full border border-gray-300 rounded-md p-2"
              />
              <Transition
                enter-from-class="opacity-0"
                leave-to-class="opacity-0"
                class="transition ease-in-out"
              >
                <p v-if="addressForm.errors.state" class="text-sm text-red-600">
                  {{ addressForm.errors.state }}
                </p>
              </Transition>
            </div>

            <div>
              <label for="zipcode">Zipcode</label>
              <input
                v-model="addressForm.zipcode"
                type="text"
                id="zipcode"
                class="w-full border border-gray-300 rounded-md p-2"
                required
              />
              <Transition
                enter-from-class="opacity-0"
                leave-to-class="opacity-0"
                class="transition ease-in-out"
              >
                <p
                  v-if="addressForm.errors.zipcode"
                  class="text-sm text-red-600"
                >
                  {{ addressForm.errors.zipcode }}
                </p>
              </Transition>
            </div>

            <div>
              <label for="country_code">Country Code</label>
              <input
                v-model="addressForm.country_code"
                type="text"
                id="country_code"
                class="w-full border border-gray-300 rounded-md p-2"
                required
              />

              <Transition
                enter-from-class="opacity-0"
                leave-to-class="opacity-0"
                class="transition ease-in-out"
              >
                <p
                  v-if="addressForm.errors.country_code"
                  class="text-sm text-red-600"
                >
                  {{ addressForm.errors.country_code }}
                </p>
              </Transition>
            </div>
          </div>
          <button
            type="submit"
            class="btn btn-primary mt-4"
            :disabled="addressForm.processing"
          >
            Add Address
          </button>
        </form>

        <!-- Display Addresses -->
        <div v-if="addresses.length === 0" class="text-center">
          <p class="text-lg">No addresses found.</p>
        </div>
        <div v-else>
          <ul>
            <li
              v-for="address in addresses"
              :key="address.id"
              class="mb-4 p-4 border border-gray-200 rounded-md"
            >
              <p><strong>Type:</strong> {{ address.type }}</p>
              <p>
                <strong>Address 1:</strong>
                {{ address.address1 }}
              </p>
              <p v-if="address.address2">
                <strong>Address 2:</strong>
                {{ address.address2 }}
              </p>
              <p><strong>City:</strong> {{ address.city }}</p>
              <p v-if="address.state">
                <strong>State:</strong> {{ address.state }}
              </p>
              <p>
                <strong>Zipcode:</strong>
                {{ address.zipcode }}
              </p>
              <p>
                <strong>Country Code:</strong>
                {{ address.country_code }}
              </p>

              <button
                @click="deleteAddress(address.id)"
                class="bg-red-500 text-white px-4 py-2 mt-2 rounded hover:bg-red-600"
              >
                Delete
              </button>
            </li>
          </ul>
        </div>
      </div>

      <div class="p-2 sm:p-4 shadow sm:rounded-lg">
        <DeleteUserForm class="max-w-xl" />
      </div>
    </div>
  </AuthLayout>
</template>
