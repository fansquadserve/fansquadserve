<script setup>
import { useTicketStore } from '@/Stores/TicketStore';
import { storeToRefs } from 'pinia';
import { onMounted, ref, computed } from 'vue';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import dayjs from 'dayjs';
import { useRouter } from 'vue-router';
import { FilterMatchMode, FilterOperator } from 'primevue/api';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';

const router = useRouter();
const ticketStore = useTicketStore();
const { getTickets, getTicketStatuses } = ticketStore;
const { tickets, ticket_statuses } = storeToRefs(ticketStore);
const tmp = ref();

// Get data for dropdown in filter
const selectTicketStatusOptions = computed(() => {
  return ticket_statuses.value.map((ticket_status) => {
    return ticket_status.name;
  });
});

const isLoading = ref(false);

async function initialize() {
  isLoading.value = true;
  await getTickets();
  await getTicketStatuses();
  await initFilters();
  isLoading.value = false;
}

onMounted(() => {
  initialize();
});

async function submit(id) {
  router.push({ name: 'ticket-details', params: { id } });
}

// Add filter for id, tutor, description, and status columns
const filters = ref();

const initFilters = () => {
  filters.value = {
    id: {
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
    },
    'tutor.full_name': {
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
    },
    description: {
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.STARTS_WITH }],
    },
    'latest_status.name': {
      operator: FilterOperator.OR,
      constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }],
    },
  };
};
</script>

<template>
  <div class="max-w-6xl mx-auto p-8 flex flex-col gap-8 justify-center">
    <h1 class="text-red-700 text-3xl font-bold">
      List of Tickets
      <font-awesome-icon icon="fa-spinner" v-if="isLoading" class="fa-spin" />
    </h1>
    <div class="flex-grow">
      <DataTable
        class="text-[14px]"
        :value="tickets"
        size="small"
        scrollable
        scrollHeight="700px"
        paginator
        :rows="10"
        stripedRows
        showGridlines
        v-model:filters="filters"
        filterDisplay="menu"
        sortField="id"
        :sortOrder="-1"
      >
        <template #empty> No tickets found. </template>

        <Column field="id" header="Ticket ID" sortable style="width: 100px">
          <template #filter="{ filterModel }">
            <InputText
              v-model="filterModel.value"
              type="text"
              class="p-column-filter"
              placeholder="Search by ID"
            />
          </template>
        </Column>

        <Column header="Request Type" style="max-width: 130px">
          <template #body="slotProps">
            <div class="singleLine">
              {{
                slotProps.data.course.id === 1
                  ? 'Tech/Lab Support'
                  : 'Peer Tutoring'
              }}
            </div>
          </template>
        </Column>

        <Column
          field="priority.name"
          header="Priority"
          sortable
          style="max-width: 80px"
        >
          <template #body="slotProps">
            <div class="singleLine">
              {{
                slotProps.data.priority ? slotProps.data.priority.name : 'N/A'
              }}
            </div>
          </template>
        </Column>

        <Column
          field="tutor.full_name"
          header="Tutor"
          sortable
          style="width: 130px"
        >
          <template #filter="{ filterModel }">
            <InputText
              v-model="filterModel.value"
              type="text"
              class="p-column-filter"
              placeholder="Search by Tutor Name"
            />
          </template>
        </Column>

        <Column field="description" header="Description" style="width: 200px">
          <template #filter="{ filterModel }">
            <InputText
              v-model="filterModel.value"
              type="text"
              class="p-column-filter"
              placeholder="Search by Description"
            />
          </template>
          <template #body="slotProps">
            <div class="singleLine">
              {{ slotProps.data.description }}
            </div>
          </template>
        </Column>

        <Column
          field="latest_status.name"
          header="Status"
          sortable
          style="width: 130px"
          :filterMenuStyle="{ width: '14rem' }"
        >
          <template #filter="{ filterModel }">
            <Dropdown
              v-model="filterModel.value"
              :options="selectTicketStatusOptions"
              placeholder="Select One"
              class="p-column-filter"
              style="min-width: 12rem"
              :showClear="true"
            >
            </Dropdown>
          </template>
        </Column>

        <Column field="created_at" sortable header="Created At">
          <template #body="slotProps">{{
            dayjs(slotProps.data.created_at).format('MMM DD, YYYY HH:mm:ss')
          }}</template>
        </Column>
        <Column header="Action">
          <template #body="slotProps">
            <button
              @click="() => submit(slotProps.data.id)"
              class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
            >
              View Details
            </button>
          </template>
        </Column>
      </DataTable>
    </div>
  </div>
</template>

<style scoped lang="postcss">
.singleLine {
  width: 200px;
  text-wrap: none;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
