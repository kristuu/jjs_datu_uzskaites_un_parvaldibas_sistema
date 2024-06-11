<template>
  <div>
    <AdminTable
      v-show="can('manage users')"
      :database-table="'users'"
      :instance-id-column="'person_code'"
      :model-name="'User'"
      :page-name="$t(`pageHeadings.users.manage users`)"
      :short-desc="$t(`pageHeadings.users.total`, { total: totalInstances })"
    >
      <DataTable
        ref="dt"
        v-model:filters="filters"
        :globalFilterFields="globalFilterFields"
        :rowClass="rowClass"
        :rows="10"
        :rowsPerPageOptions="[10, 15, 20, 50]"
        :value="instances"
        csvSeparator=";"
        filterDisplay="menu"
        paginator
        removableSort
        selectionMode="single"
        size="small"
        stripedRows
        @rowSelect="
          (e) => {
            onRowSelect(e);
          }
        "
      >
        <template #header>
          <div class="d-flex justify-content-between flex-wrap mb-2 mt-2">
            <Button
              v-if="can('create instances')"
              icon="bi bi-plus-lg"
              raised
              rounded
              @click="router.push({ name: 'CreateUser' })"
            >
            </Button>
            <div class="flex gap-2">
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
            <span>{{ $t(`table.empty`) }}</span>
          </div>
        </template>
        <Column
          :header="$t('table.users.person_code')"
          field="person_code"
          sortable
        >
          <template #body="{ data }">
            {{
              `${data.person_code?.slice(0, 6)}-${data.person_code?.slice(
                6,
                12
              )}`
            }}
          </template>
          <template #filter="{ filterModel }">
            <InputText v-model="filterModel.value" type="text" />
          </template>
        </Column>
        <Column :header="$t('table.users.name')" field="name" sortable>
          <template #body="{ data }">
            {{ data.name }}
          </template>
          <template #filter="{ filterModel }">
            <InputText v-model="filterModel.value" type="text" />
          </template>
        </Column>
        <Column :header="$t('table.users.surname')" field="surname" sortable>
          <template #body="{ data }">
            {{ data.surname }}
          </template>
          <template #filter="{ filterModel }">
            <InputText v-model="filterModel.value" type="text" />
          </template>
        </Column>
        <Column :header="$t(`table.users.email`)" field="email" sortable>
          <template #body="{ data }">
            {{ data.email }}
          </template>
          <template #filter="{ filterModel }">
            <InputText v-model="filterModel.value" type="text" />
          </template>
        </Column>
        <Column :exportable="false">
          <template #body="{ data }">
            <Button
              icon="bi bi-trash-fill"
              outlined
              rounded
              @click="fetchDataStore.deleteInstance(`users`, data.person_code)"
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
              $t(`details_sidebar.user`)
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
              <div class="d-flex flex-column col-lg-4 col-sm-6 col-12">
                <label>{{ $t(`table.users.person_code`) }}</label>
                <span>{{
                  `${instance.person_code?.slice(
                    0,
                    6
                  )}-${instance.person_code?.slice(6, 12)}`
                }}</span>
              </div>
              <div class="d-flex flex-column col-lg-4 col-sm-6 col-12">
                <label>{{ $t(`table.users.name`) }}</label>
                <span>{{ instance.name }}</span>
              </div>
              <div class="d-flex flex-column col-lg-4 col-sm-6 col-12">
                <label>{{ $t(`table.users.surname`) }}</label>
                <span>{{ instance.surname }}</span>
              </div>
              <div class="d-flex flex-column col-lg-4 col-sm-6 col-12">
                <label>{{ $t(`table.users.birthdate`) }}</label>
                <span>{{ instance.birthdate }}</span>
              </div>
              <Fieldset legend="Kontaktinformācija">
                <div class="row mx-1">
                  <div class="d-flex flex-column col-12 sm:col-6 lg:col-4">
                    <label>{{ $t(`table.users.email`) }}</label>
                    <span>{{ instance.email }}</span>
                  </div>
                  <div class="d-flex flex-column col-12 sm:col-6 lg:col-4">
                    <label>{{ $t(`table.users.phone`) }}</label>
                    <span>{{ instance.phone }}</span>
                  </div>
                </div>
              </Fieldset>
              <div class="d-flex flex-column col-12 sm:col-6 lg:col-4">
                <label>{{ $t(`table.users.iban_code`) }}</label>
                <span>{{ instance.iban_code ?? `-` }}</span>
              </div>
              <Fieldset legend="Lomas" toggleable>
                <div class="row mx-1">
                  <template v-for="role in instance.all_roles">
                    <div
                      v-if="role.userHas"
                      class="d-flex flex-column col-12 md:col-6 lg:col-4"
                    >
                      <Chip
                        :label="role.name"
                        class="justify-content-center"
                        style="height: 100%"
                      />
                    </div>
                  </template>
                </div>
              </Fieldset>
            </div>
          </div>
          <div class="mt-auto">
            <hr class="mb-3 mx-3 border-top-1 border-none surface-border" />
            <div class="m-3 flex justify-content-between gap-3 text-primary">
              <router-link
                :to="{ name: `EditUser`, params: { id: instance.person_code } }"
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
                    fetchDataStore.deleteInstance(
                      `users`,
                      instance.person_code
                    );
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
import { computed, onBeforeMount, onMounted, ref } from "vue";
import AdminTable from "@/components/AdminTable.vue";
import { FilterMatchMode, FilterOperator } from "primevue/api";

import { useFetchDataStore } from "@/stores/fetchDataStore";
import router from "@/router/router";

const fetchDataStore = useFetchDataStore();

const instance = computed(() => fetchDataStore.instance);
const instances = computed(() => fetchDataStore.allInstances);
const totalInstances = computed(() => fetchDataStore.totalInstanceCount);

const globalFilterFields = ref([
  "name",
  "surname",
  "person_code",
  "birth_date",
  "phone",
  "email",
  "iban_code",
]);

const filters = ref();

const dt = ref();
const exportCSV = () => {
  dt.value.exportCSV();
};

const initfilters = () => {
  const defaultTextContainsFilter = () => ({
    operator: FilterOperator.AND,
    constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }],
  });

  filters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    name: defaultTextContainsFilter(),
    surname: defaultTextContainsFilter(),
    person_code: defaultTextContainsFilter(),
    birthdate: {
      sortable: true,
      filterType: "date",
      dataType: "date",
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.DATE_IS }],
    },
    email: defaultTextContainsFilter(),
    phone: defaultTextContainsFilter(),
    iban_code: defaultTextContainsFilter(),
  };
};

initfilters();

const onRowSelect = async (event) => {
  await fetchDataStore.fetchInstance(`users`, event.data.person_code);
  visible.value = true;
};
let visible = ref(false);

onBeforeMount(async () => {
  await fetchDataStore.fetchDatabaseData("users");
});

onMounted(() => {
  fetchDataStore.showComponents();
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
