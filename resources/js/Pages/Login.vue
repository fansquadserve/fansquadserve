<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/Stores/AuthStore';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';

const toast = useToast();
const router = useRouter();
const authStore = useAuthStore();
const { login, error, clearError } = authStore;

async function submit(values) {
  clearError();
  try {
    await login({ email: values.data.email, password: values.data.password });
    router.push({ name: 'tickets' });
  } catch {
    toast.error(
      'Unable to login. Please use your @fanshaweonline.ca or @fanshawec.ca email address and password!',
    );
  }
}
</script>

<template>
  <div
    class="h-screen flex flex-col gap-8 justify-center items-center max-w-xl mx-auto"
  >
    <router-link :to="{ name: 'landing' }">
      <img class="w-64" src="../../images/logo.png" alt="FanSquadServe Logo" />
    </router-link>

    <div class="flex flex-col gap-2">
      <h1 class="text-xl text-red-700 font-bold">LabSquad Log In</h1>
      <div class="p-6 bg-gray-100 border-gray-800 border w-96">
        <Vueform
          :columns="{ container: 12, wrapper: 12 }"
          :display-errors="false"
          :endpoint="false"
          @submit="submit"
        >
          <TextElement
            name="email"
            label="Email *"
            :rules="['required', 'email']"
          />
          <TextElement
            name="password"
            label="Password *"
            input-type="password"
            :rules="['required']"
          />
          <ButtonElement
            name="button"
            button-class="font-semibold"
            submits
            class="mx-auto mt-4"
            :override-classes="{
              ButtonElement: {
                button_primary: 'bg-red-700 text-white',
              },
            }"
            >Log In</ButtonElement
          >
        </Vueform>
      </div>
      <router-link
        :to="{ name: 'landing' }"
        class="col-span-12 text-center text-sm underline text-blue-500"
      >
        <a>Back to Home Page</a>
      </router-link>
    </div>
  </div>
</template>
