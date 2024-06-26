<script setup>
import { createUser } from '@/Services/UserService';
import { storeToRefs } from 'pinia';
import { useUserStore } from '@/Stores/UserStore';
import { computed, onMounted, ref, watch } from 'vue';
import { useToast } from 'vue-toastification';
import { useRouter } from 'vue-router';

const router = useRouter();
const userStore = useUserStore();
const { getCourses, getRoles } = userStore;

const { courses, roles } = storeToRefs(userStore);

const formRef = ref(null);
const visible = ref(false);
const user = ref(null);
const toast = useToast();

// Get data for dropdowns
const selectCourseOptions = computed(() => {
  return courses.value.map((course) => {
    return {
      value: course.id,
      label: course.name,
      code: course.code,
    };
  });
});

const selectRoleOptions = computed(() => {
  return roles.value.map((role) => {
    return {
      value: role.id,
      label: role.name,
    };
  });
});

onMounted(() => {
  getCourses();
  getRoles();
});

// Update is_tutor to show/hide optional Course & Schedule Page fields
const is_tutor = ref(false);

function selectRoles(option) {
  if (option === 1) {
    is_tutor.value = true;
  }
}

function deselectRoles(option) {
  if (option === 1) {
    is_tutor.value = false;
  }
}

async function submitUser(values) {
  let submitted_email = values.requestData.email;
  if (
    submitted_email.includes('@fanshaweonline.ca') ||
    submitted_email.includes('@fanshawec.ca')
  ) {
    try {
      const data = await createUser(values.requestData);
      user.value = data;
      visible.value = true;
      formRef.value.reset();

      toast.success('Create user successfully!');
      router.push({ name: 'users' });
    } catch {
      toast.error('Unable to create user. Please try again!');
    }
  } else {
    toast.error(
      'Unable to create user. Please use your @fanshaweonline.ca or @fanshawec.ca email address!',
    );
  }
}
</script>
<template>
  <div
    class="max-w-4xl mx-auto p-8 flex flex-col gap-8 max-h-screen overflow-auto flex-grow"
  >
    <h1 class="text-red-700 text-3xl font-bold flex-shrink-0">Add User</h1>
    <div class="p-8 shadow flex flex-col gap-4 flex-grow bg-gray-50">
      <Vueform
        ref="formRef"
        @submit="submitUser"
        :endpoint="false"
        :display-errors="false"
      >
        <TextElement
          name="first_name"
          label="First Name *"
          input-type="text"
          :rules="['required', 'alpha', 'between:3,50']"
          class="col-span-6"
        />
        <TextElement
          name="last_name"
          label="Last Name *"
          input-type="text"
          class="col-span-6"
          :rules="['required', 'alpha', 'between:3,50']"
        />
        <TextElement
          name="email"
          label="Email *"
          input-type="email"
          :rules="['required', 'email']"
          class="col-span-6"
        >
          <template v-slot:description="{ el$ }">
            <div>Please use your Fanshawe domain email.</div>
          </template>
        </TextElement>
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <TextElement
          name="password"
          label="Password *"
          input-type="password"
          class="col-span-6"
          :rules="['required', 'between:8,255', 'confirmed']"
        />
        <TextElement
          name="password_confirmation"
          label="Password Confirmation *"
          input-type="password"
          :rules="['required', 'between:8,255']"
          class="col-span-6"
        />
        <StaticElement name="divider">
          <hr />
        </StaticElement>
        <MultiselectElement
          name="roles"
          label="Role *"
          :native="false"
          :items="selectRoleOptions"
          class="col-span-6"
          :close-on-select="false"
          :hide-selected="false"
          @select="selectRoles"
          @deselect="deselectRoles"
          :can-clear="false"
          :rules="['required']"
        >
          <template v-slot:description="{ el$ }">
            <div>
              Course and Tutor's Schedule Page fields are required if role Tutor
              is selected.
            </div>
          </template>
        </MultiselectElement>
        <MultiselectElement
          name="courses"
          label="Course"
          :native="false"
          :items="selectCourseOptions"
          class="col-span-6"
          :close-on-select="false"
          :hide-selected="false"
          :search="true"
          :conditions="[(form$, el$) => form$.el$('roles')?.value.includes(1)]"
        >
          <template v-slot:option="{ option }">
            {{ option.code }} - {{ option.label }}
          </template>
        </MultiselectElement>
        <TextElement
          name="schedule_page"
          label="Tutor's Schedule Page"
          class="col-span-6"
          input-type="text"
          :conditions="[(form$, el$) => form$.el$('roles')?.value.includes(1)]"
        />
        <ButtonElement
          name="button"
          button-class="font-semibold"
          submits
          class="mx-auto mt-4"
          :override-classes="{
            ButtonElement: {
              button_primary: 'bg-red-700 text-white'
            }
          }"
        >
          Create User
        </ButtonElement>
      </Vueform>
    </div>
  </div>
</template>
