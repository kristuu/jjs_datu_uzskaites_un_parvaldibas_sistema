<template>
  <div>
    <AdminTable
        v-show="can('manage users')"
        :page-name="$t(`pageHeadings.users.manage users`)"
        :database-table="'users'"
        :model-name="'User'"
        :instance-id-column="'person_code'"
        :short-desc="$t(`pageHeadings.users.total`, {total: totalInstances})"
    >
      <DataTable :value="instances" size="small" stripedRows removableSort
                 paginator :rows="10" :rowsPerPageOptions="[10, 15, 20, 50]"
                 v-model:filters="filters" filterDisplay="menu" :globalFilterFields="globalFilterFields"
                 :rowClass="rowClass" selectionMode="single" @rowSelect="(e) => { onRowSelect(e) }">
        <template #header>
          <div class="d-flex justify-content-between flex-wrap mb-2 mt-2">
            <Button v-if="can('create instances')"
                    icon="bi bi-plus-lg"
                    rounded raised
                    @click="router.push({ name: 'CreateUser' })">
            </Button>
            <IconField iconPosition="left">
              <InputIcon>
                <i class="bi bi-search" />
              </InputIcon>
              <InputText v-model="filters['global'].value" :placeholder="$t(`table.search`)" />
            </IconField>
          </div>
        </template>
        <template #empty>
          <div class="d-flex flex-column gap-2" v-if="fetchDataStore.isFetching">
            <template v-for="i in [1, 2, 3, 4]">
              <Skeleton height="2rem" />
              <Divider class="m-0"/>
            </template>
            <Skeleton height="2rem" />
          </div>
          <div class="text-center" v-else>
            <span>{{ $t(`table.empty`) }}</span>
          </div>
        </template>
        <Column field="person_code" :header="$t('table.users.person_code')" sortable>
          <template #body="{ data }" >
            {{ `${data.person_code?.slice(0, 6)}-${data.person_code?.slice(6, 12)}` }}
          </template>
          <template #filter="{ filterModel }">
            <InputText
                type="text"
                v-model="filterModel.value"/>
          </template>
        </Column>
        <Column field="name" :header="$t('table.users.name')" sortable>
          <template #body="{ data }">
            {{ data.name }}
          </template>
          <template #filter="{ filterModel }">
            <InputText
                type="text"
                v-model="filterModel.value"/>
          </template>
        </Column>
        <Column field="surname" :header="$t('table.users.surname')" sortable>
          <template #body="{ data }">
            {{ data.surname }}
          </template>
          <template #filter="{ filterModel }">
            <InputText
                type="text"
                v-model="filterModel.value"/>
          </template>
        </Column>
        <Column field="email" :header="$t(`table.users.email`)" sortable>
          <template #body="{ data }">
            {{ data.email }}
          </template>
          <template #filter="{ filterModel }">
            <InputText
                type="text"
                v-model="filterModel.value"/>
          </template>
        </Column>
        <Column :exportable="false">
          <template #body="{ data }">
            <Button icon="bi bi-trash-fill" @click="fetchDataStore.deleteInstance(`users`, data.person_code)" outlined rounded />
          </template>
        </Column>
      </DataTable>
    </AdminTable>

    <Sidebar v-model:visible="visible" position="bottom" style="height:40rem; max-height: 90vh;"
             blockScroll="true">
      <template #container="{ closeCallback }">
        <div class="flex flex-column h-full container">
          <div class="flex align-items-center justify-content-between px-4 pt-3 flex-shrink-0">
            <img src="@/assets/logo-red.svg" width="50" />
            <span class="font-semibold text-2xl text-primary ms-1">{{ $t(`details_sidebar.user`) }}</span>
            <Button type="button" @click="closeCallback" icon="pi pi-times" rounded outlined class="h-2rem w-2rem"></Button>
          </div>
          <Divider />
          <div class="overflow-y-auto w-100">
            <div class="row g-3 container-fluid mx-auto">
              <div class="d-flex flex-column col-lg-4 col-sm-6 col-12">
                <label>{{ $t(`table.users.person_code`) }}</label>
                <span>{{ `${instance.person_code?.slice(0, 6)}-${instance.person_code?.slice(6, 12)}` }}</span>
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
                        class="d-flex flex-column col-12 md:col-6 lg:col-4"
                        v-if="role.userHas"
                    >
                      <Chip
                          :label="role.name"
                          style="height: 100%;"
                          class="justify-content-center"
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
              <router-link :to="{ name: `EditUser`, params: { id: instance.person_code } }" @click="visible = false;">
                <span class="font-bold"><i class="bi bi-pencil-fill"/> {{ $t(`table.edit`) }}</span>
              </router-link>
              <span class="font-bold cursor-pointer" @click="() => { fetchDataStore.deleteInstance(`users`, instance.person_code); visible = false; }">{{ $t(`table.delete`) }} <i class="bi bi-trash-fill"/></span>
            </div>
          </div>
        </div>
      </template>
    </Sidebar>
  </div>
</template>

<script setup>
import {computed, onBeforeMount, onMounted, ref} from 'vue';
import AdminTable from '@/components/AdminTable.vue';
import {FilterMatchMode, FilterOperator} from "primevue/api";

import { useFetchDataStore } from "@/stores/fetchDataStore";
import router from "@/router/router";
const fetchDataStore = useFetchDataStore();

const instance = computed(() => fetchDataStore.instance);
const instances = computed(() => fetchDataStore.allInstances);
const totalInstances = computed(() => fetchDataStore.totalInstanceCount);

const globalFilterFields = ref([
    "name", "surname", "person_code", "birth_date",
    "phone", "email", "iban_code"
]);

const filters = ref();

const initfilters = () => {
  const defaultTextContainsFilter = () => ({
    operator: FilterOperator.AND,
    constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }]
  });

  filters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    name: defaultTextContainsFilter(),
    surname: defaultTextContainsFilter(),
    person_code: defaultTextContainsFilter(),
    birthdate: {
      sortable: true, filterType: 'date', dataType: 'date',
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.DATE_IS }]
    },
    email: defaultTextContainsFilter(),
    phone: defaultTextContainsFilter(),
    iban_code: defaultTextContainsFilter(),
  }
};

initfilters();

const onRowSelect = async (event) => {
  await fetchDataStore.fetchInstance(`users`, event.data.person_code);
  visible.value = true;
}
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

th, h5 {
  font-weight: bold;
}

nav ul {
  margin-bottom: 0 !important;
}
</style>

