<template>
  <div>
    <AdminTable
      v-if="!fetchDataStore.isLoading"
      :database-table="'reservations'"
      :instance-id-column="'id'"
      :model-name="'Reservation'"
      :page-name="$t('pageHeadings.reservations.manage reservations')"
    >
      <DataTable
        v-model:filters="filters"
        :globalFilterFields="globalFilterFields"
        :rowClass="rowClass"
        :rows="10"
        :rowsPerPageOptions="[10, 15, 20, 50]"
        :value="instances"
        filterDisplay="menu"
        paginator
        removableSort
        selectionMode="single"
        size="small"
        stripedRows
        @rowSelect="onRowSelect"
      >
        <template #header>
          <div
            class="d-flex flex-column-reverse gap-2 sm:flex-row justify-content-between flex-wrap mb-2 mt-2"
          >
            <Button
              v-if="can('create instances')"
              icon="bi bi-plus-lg"
              raised
              rounded
              @click="router.push({ name: 'CreateReservation' })"
            >
            </Button>
            <div class="flex flex-column sm:flex-row gap-2">
              <Button
                icon="pi pi-external-link"
                label="Eksportēt CSV"
                @click="exportCSV($event)"
              />
              <IconField iconPosition="left">
                <InputIcon>
                  <i class="bi bi-search" />
                </InputIcon>
                <InputText
                  v-model="filters['global'].value"
                  :placeholder="$t(`table.search`)"
                  class="w-100"
                />
              </IconField>
            </div>
          </div>
        </template>
        <template #empty>
          <div
            v-if="fetchDataStore.isFetching"
            class="d-flex flex-column gap-2"
          >
            <template v-for="i in [1, 2, 3, 4]">
              <Skeleton height="2rem" />
              <Divider class="m-0" />
            </template>
            <Skeleton height="2rem" />
          </div>
          <div v-else class="text-center">
            <span>{{ $t("table.empty") }}</span>
          </div>
        </template>
        <Column
          :header="$t('table.reservations.user')"
          field="user.name"
          sortable
        >
          <template #body="{ data }"
            >{{ data.user.name + " " + data.user.surname }}
          </template>
          <template #filter="{ filterModel }">
            <InputText v-model="filterModel.value" type="text" />
          </template>
        </Column>
        <Column
          :header="$t('table.reservations.instructor')"
          field="instructor.name"
          sortable
        >
          <template #body="{ data }"
            >{{
              data.instructor.user.name + " " + data.instructor.user.surname
            }}
          </template>
          <template #filter="{ filterModel }">
            <InputText v-model="filterModel.value" type="text" />
          </template>
        </Column>
        <Column
          :header="$t('table.reservations.category')"
          field="instructor.certificate.category.name"
          sortable
        >
          <template #body="{ data }"
            >{{ data.instructor.certificate.category.name }}
          </template>
          <template #filter="{ filterModel }">
            <InputText v-model="filterModel.value" type="text" />
          </template>
        </Column>
        <Column
          :header="$t('table.reservations.time')"
          field="instructor_availability.start_time"
          sortable
        >
          <template #body="{ data }"
            >{{
              `${moment(data.instructor_availability.start_time).format(
                "DD.MM.YYYY HH.mm"
              )} - ${moment(data.instructor_availability.end_time).format(
                "DD.MM.YYYY HH.mm"
              )}`
            }}
          </template>
          <template #filter="{ filterModel }">
            <InputText v-model="filterModel.value" type="text" />
          </template>
        </Column>
        <Column
          :header="$t('table.reservations.status')"
          field="status"
          sortable
        >
          <template #body="{ data }">
            <div class="text-center">
              <Tag
                :severity="statusSeverity(data.status)"
                :value="$t(`reservations.` + data.status).toUpperCase()"
                style="left: 4px; top: 4px"
              />
            </div>
          </template>
          <template #filter="{ filterModel }">
            <Dropdown
              v-model="filterModel.value"
              :options="statuses"
              optionLabel="label"
              optionValue="value"
              placeholder="Select a Status"
            />
          </template>
        </Column>
        <Column :exportable="false">
          <template #body="{ data }">
            <Button
              icon="bi bi-trash-fill"
              outlined
              rounded
              @click="fetchDataStore.deleteInstance('reservations', data.id)"
            />
          </template>
        </Column>
      </DataTable>
    </AdminTable>

    <Sidebar
      v-model:visible="visible"
      blockScroll="true"
      position="bottom"
      style="height: 40rem; max-height: 90vh"
    >
      <template #container="{ closeCallback }">
        <div class="flex flex-column h-full container">
          <div
            class="flex align-items-center justify-content-between px-4 pt-3 flex-shrink-0"
          >
            <img src="@/assets/logo-red.svg" width="50" />
            <span class="font-semibold text-2xl text-primary ms-1">{{
              $t(`details_sidebar.reservation`)
            }}</span>
            <Button
              class="h-2rem w-2rem"
              icon="pi pi-times"
              outlined
              rounded
              type="button"
              @click="closeCallback"
            ></Button>
          </div>
          <Divider />
          <div class="overflow-y-auto w-100">
            <div class="row g-3 container-fluid mx-auto">
              <Fieldset legend="Lietotājs">
                <div class="row mx-1">
                  <div class="d-flex flex-column col-12 lg:col-4 sm:col-6">
                    <label>{{ $t(`table.reservations.user_names`) }}</label>
                    <span>{{
                      `${instance.user.name} ${instance.user.surname}`
                    }}</span>
                  </div>
                  <div class="d-flex flex-column col-12 lg:col-4 sm:col-6">
                    <label>{{ $t(`table.users.person_code`) }}</label>
                    <span>{{ instance.user.person_code }}</span>
                  </div>
                </div>
              </Fieldset>
            </div>
          </div>
          <div class="mt-auto">
            <hr class="mb-3 mx-3 border-top-1 border-none surface-border" />
            <div class="m-3 flex justify-content-between gap-3 text-primary">
              <router-link
                :to="{ name: `EditReservation`, params: { id: instance.id } }"
                @click="visible = false"
              >
                <span class="font-bold"
                  ><i class="bi bi-pencil-fill" /> {{ $t(`table.edit`) }}</span
                >
              </router-link>
              <span
                class="font-bold cursor-pointer"
                @click="
                  () => {
                    fetchDataStore.deleteInstance(`reservations`, instance.id);
                    visible = false;
                  }
                "
                >{{ $t(`table.delete`) }} <i class="bi bi-trash-fill"
              /></span>
            </div>
          </div>
        </div>
      </template>
    </Sidebar>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import AdminTable from "@/components/AdminTable.vue";
import { FilterMatchMode, FilterOperator } from "primevue/api";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import router from "@/router/router";
import { useI18n } from "vue-i18n";
import Tag from "primevue/tag";
import moment from "moment";

const { t } = useI18n();
const fetchDataStore = useFetchDataStore();

const instance = computed(() => fetchDataStore.instance);
const instances = computed(() => fetchDataStore.allInstances);
const totalInstances = computed(() => fetchDataStore.totalInstanceCount);

const globalFilterFields = ref([
  "id",
  "user_person_code",
  "user.name",
  "instructor.name",
  "status_label", // Use status_label for global filtering
]);

const filters = ref({});
const statuses = ref([
  { label: t(`reservations.submitted`), value: "submitted" },
  { label: t(`reservations.accepted`), value: "accepted" },
  { label: t(`reservations.denied`), value: "denied" },
]);

const initFilters = () => {
  const defaultTextContainsFilter = () => ({
    operator: FilterOperator.AND,
    constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }],
  });

  filters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    id: defaultTextContainsFilter(),
    "user.name": defaultTextContainsFilter(),
    "instructor.name": defaultTextContainsFilter(),
    status: {
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.EQUALS }],
    },
    status_label: defaultTextContainsFilter(), // Add status_label for global filtering
  };
};

initFilters();

const statusSeverity = (status) => {
  return status === "submitted"
    ? "info"
    : status === "accepted"
    ? "success"
    : "danger";
};

const getStatusLabel = (status) => {
  const statusObj = statuses.value.find((s) => s.value === status);
  return statusObj ? statusObj.label : status;
};

const onRowSelect = async (event) => {
  await fetchDataStore.fetchInstance("reservations", event.data.id);
  visible.value = true;
};

let visible = ref(false);

onMounted(async () => {
  await fetchDataStore.fetchDatabaseData("reservations");

  // Map status values to labels for global filtering
  fetchDataStore.allInstances.forEach((instance) => {
    instance.status_label = getStatusLabel(instance.status);
  });
});
</script>

<style scoped>
p {
  margin: 0;
}

th,
h5 {
  font-weight: bold;
}

nav ul {
  margin-bottom: 0 !important;
}
</style>
