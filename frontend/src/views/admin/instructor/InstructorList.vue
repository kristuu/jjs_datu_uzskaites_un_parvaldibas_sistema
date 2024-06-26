<template>
  <div>
    <AdminTable
      v-if="can('manage instructors') && !fetchDataStore.isLoading"
      :database-table="'instructors'"
      :instance-id-column="'id'"
      :model-name="'Instructor'"
      :page-name="$t(`pageHeadings.instructors.manage instructors`)"
      :short-desc="
        $t(`pageHeadings.instructors.total`, { total: totalInstances })
      "
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
        @rowSelect="
          (e) => {
            onRowSelect(e);
            checkCertificateExpiry();
          }
        "
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
              @click="router.push({ name: 'CreateInstructor' })"
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
            <span>{{ $t(`table.empty`) }}</span>
          </div>
        </template>
        <Column :header="$t('table.id')" field="id">
          <template #body="{ data }">
            {{ data.id }}
          </template>
          <template #filter="{ filterModel }">
            <InputText v-model="filterModel.value" type="text" />
          </template>
        </Column>
        <Column
          :header="$t('table.users.person_code')"
          field="user.person_code"
          sortable
        >
          <template #body="{ data }">
            {{
              `${data.user?.person_code?.slice(
                0,
                6
              )}-${data.user.person_code?.slice(6, 12)}`
            }}
          </template>
          <template #filter="{ filterModel }">
            <InputText v-model="filterModel.value" type="text" />
          </template>
        </Column>
        <Column :header="$t('table.users.name')" field="user.name" sortable>
          <template #body="{ data }">
            {{ data.user?.name }}
          </template>
          <template #filter="{ filterModel }">
            <InputText v-model="filterModel.value" type="text" />
          </template>
        </Column>
        <Column
          :header="$t('table.users.surname')"
          field="user.surname"
          sortable
        >
          <template #body="{ data }">
            {{ data.user?.surname }}
          </template>
          <template #filter="{ filterModel }">
            <InputText v-model="filterModel.value" type="text" />
          </template>
        </Column>
        <Column
          :header="$t('table.instructors.categories.name')"
          field="certificate.category.name"
          sortable
        >
          <template #body="{ data }">
            {{ data.certificate?.category?.name }}
          </template>
          <template #filter="{ filterModel }">
            <InputText v-model="filterModel.value" type="text" />
          </template>
        </Column>
        <Column
          :header="$t('table.instructors.certificates.expiration_date')"
          dataType="date"
          field="certificate.expiration_date"
          filterType="date"
          sortable
        >
          <template #body="{ data }">
            {{ data.certificate?.expiration_date }}
          </template>
          <template #filter="{ filterModel }">
            <Calendar
              v-model="filterModel.value"
              dateFormat="dd.mm.yy"
              type="date"
            />
          </template>
        </Column>
        <Column :exportable="false">
          <template #body="{ data }">
            <Button
              icon="bi bi-trash-fill"
              outlined
              rounded
              @click="fetchDataStore.deleteInstance(`instructors`, data.id)"
            />
          </template>
        </Column>
      </DataTable>
    </AdminTable>

    <Sidebar
      v-model:visible="visible"
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
              <div class="d-flex flex-column col-sm-6 col-12">
                <label>ID</label>
                <span>{{ instance.id }}</span>
              </div>
              <div class="d-flex flex-column col-sm-6 col-12">
                <label>{{ $t(`table.instructors.job_start_date`) }}</label>
                <span>{{ instance.job_start_date }}</span>
              </div>
              <div class="d-flex flex-column col-12">
                <label>{{ $t(`table.instructors.short_description`) }}</label>
                <span>{{ instance.short_description }}</span>
              </div>
              <div class="d-flex flex-column col-12">
                <label>{{ $t(`table.instructors.description`) }}</label>
                <span>{{ instance.description }}</span>
              </div>
              <Fieldset
                :legend="$t(`table.instructors.certificate`)"
                toggleable
              >
                <div class="row mx-1">
                  <div class="d-flex flex-column col-lg-3 col-sm-4 col-12">
                    <label>{{ $t(`table.instructors.categories.name`) }}</label>
                    <span>{{ instance.certificate?.category?.name }}</span>
                  </div>
                  <div class="d-flex flex-column col-lg-3 col-sm-4 col-12">
                    <label>{{
                      $t(`table.instructors.certificates.expiration_date`)
                    }}</label>
                    <span>{{ instance.certificate?.expiration_date }}</span>
                  </div>
                  <div class="col-12">
                    <Message
                      v-for="msg in messages"
                      :key="msg.id"
                      :closable="false"
                      :severity="msg.severity"
                      >{{ msg.content }}
                    </Message>
                  </div>
                </div>
              </Fieldset>
              <Fieldset :legend="$t(`table.instructors.user`)" toggleable>
                <div class="row mx-1">
                  <div class="d-flex flex-column col-lg-3 col-sm-4 col-12">
                    <label>{{ $t(`table.users.name`) }}</label>
                    <span>{{ instance.user?.name }}</span>
                  </div>
                  <div class="d-flex flex-column col-lg-3 col-sm-4 col-12">
                    <label>{{ $t(`table.users.surname`) }}</label>
                    <span>{{ instance.user?.surname }}</span>
                  </div>
                  <div class="d-flex flex-column col-lg-3 col-sm-4 col-12">
                    <label>{{ $t(`table.users.person_code`) }}</label>
                    <span>{{
                      `${instance.user?.person_code?.slice(
                        0,
                        6
                      )}-${instance.user.person_code?.slice(6, 12)}`
                    }}</span>
                  </div>
                  <div class="d-flex flex-column col-lg-3 col-sm-4 col-12">
                    <label>{{ $t(`table.users.birthdate`) }}</label>
                    <span>{{ instance.user?.birthdate }}</span>
                  </div>
                  <div class="d-flex flex-column col-lg-3 col-sm-4 col-12">
                    <label>{{ $t(`table.users.phone`) }}</label>
                    <span>{{ instance.user?.phone }}</span>
                  </div>
                  <div class="d-flex flex-column col-lg-3 col-sm-4 col-12">
                    <label>{{ $t(`table.users.email`) }}</label>
                    <span>{{ instance.user?.email }}</span>
                  </div>
                </div>
              </Fieldset>
            </div>
          </div>
          <div class="mt-auto">
            <hr class="mb-3 mx-3 border-top-1 border-none surface-border" />
            <div class="m-3 flex justify-content-between gap-3 text-primary">
              <router-link
                :to="{ name: `EditInstructor`, params: { id: instance.id } }"
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
                    fetchDataStore.deleteInstance(`instructors`, instance.id);
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
import { useDateStore } from "@/stores/dateStore";
import router from "@/router/router";

const fetchDataStore = useFetchDataStore();
const dateStore = useDateStore();

const instance = computed(() => fetchDataStore.instance);
const instances = computed(() => fetchDataStore.allInstances);
const totalInstances = computed(() => fetchDataStore.totalInstanceCount);

const globalFilterFields = ref([
  "id",
  "user.person_code",
  "user.name",
  "user.surname",
  "certificate.category.name",
]);
const filters = ref();

const initFilters = () => {
  const defaultTextContainsFilter = () => ({
    operator: FilterOperator.AND,
    constraints: [{ value: null, matchMode: FilterMatchMode.CONTAINS }],
  });

  filters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    id: defaultTextContainsFilter(),
    "user.person_code": defaultTextContainsFilter(),
    "user.name": defaultTextContainsFilter(),
    "user.surname": defaultTextContainsFilter(),
    "certificate.category.name": defaultTextContainsFilter(),
    "certificate.expiration_date": {
      operator: FilterOperator.AND,
      constraints: [{ value: null, matchMode: FilterMatchMode.DATE_IS }],
    },
  };
};

initFilters();

const onRowSelect = async (event) => {
  await fetchDataStore.fetchInstance(`instructors`, event.data.id);
  await checkCertificateExpiry();
  visible.value = true;
};

const msPerMonth = 1000 * 60 * 60 * 24 * 30;

const rowClass = (data) => {
  if (!fetchDataStore.isLoading) {
    const currentDate = new Date();
    const expDate = dateStore.parseLVstringDate(
      data.certificate?.expiration_date
    );

    expDate.setHours(0, 0, 0, 0);
    currentDate.setHours(0, 0, 0, 0);

    const timeLeft = expDate.getTime() - currentDate.getTime();
    return [
      {
        "bg-red-100 text-red": timeLeft < 0,
        "bg-yellow-100 text-red": timeLeft <= 3 * msPerMonth && timeLeft >= 0,
      },
    ];
  }
};

let visible = ref(false);

const messages = ref({});
const count = ref(0);

const checkCertificateExpiry = () => {
  if (!fetchDataStore.isLoading) {
    console.log("test after loading");
    messages.value = {};
    let expirationDate = dateStore.parseLVstringDate(
      instance.value.certificate?.expiration_date
    );
    let currentDate = new Date();

    expirationDate.setHours(0, 0, 0, 0);
    currentDate.setHours(0, 0, 0, 0);
    let threeMonths = new Date();
    threeMonths.setMonth(currentDate.getMonth() + 3);

    if (expirationDate < currentDate) {
      messages.value.push = {
        severity: "error",
        content: "Sertifikāta derīgums ir beidzies",
        id: count.value++,
      };
      console.log(messages.value);
    } else if (expirationDate <= threeMonths) {
      const oneDayMiliseconds = 1000 * 60 * 60 * 24;
      let diff = Math.floor(
        (expirationDate - currentDate) / oneDayMiliseconds + 1
      );
      messages.value.push = {
        severity: "warn",
        content: `Sertifikāta derīgums beigsies pēc ${diff} dienām`,
        id: count.value++,
      };
    }
  }
};

onBeforeMount(async () => {
  await fetchDataStore.fetchDatabaseData("instructors");
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
