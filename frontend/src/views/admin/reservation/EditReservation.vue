<template>
  <AdminForm
    :database-table="'reservations'"
    :model-name="'Reservation'"
    :page-name="$t('pageHeadings.reservations.edit reservation')"
    :short-desc="$t('pageHeadings.reservations.edit an existing reservation')"
  >
    <form @submit.prevent="submitForm">
      <Panel header="Reservation Details" toggleable>
        <div class="p-fluid formgrid grid">
          <div class="field col-12 md:col-6">
            <label for="user">{{ $t("form.user") }}</label>
            <Dropdown
              v-model="form.user_person_code"
              :options="users"
              dataKey="person_code"
              filter
              filterBy="name"
              optionLabel="name"
              placeholder="Select a User"
              showClear
            />
          </div>
          <div class="field col-12 md:col-6">
            <label for="instructor">{{ $t("form.instructor") }}</label>
            <Dropdown
              v-model="form.instructor_id"
              :options="instructors"
              dataKey="id"
              filter
              filterBy="name"
              optionLabel="name"
              placeholder="Select an Instructor"
              showClear
            />
          </div>
          <div class="field col-12 md:col-6">
            <label for="availability">{{ $t("form.availability") }}</label>
            <Dropdown
              v-model="form.instructor_availability_id"
              :options="availabilities"
              dataKey="id"
              filter
              filterBy="name"
              optionLabel="name"
              placeholder="Select Availability"
              showClear
            />
          </div>
          <div class="field col-12 md:col-6">
            <label for="status">{{ $t("form.status") }}</label>
            <Dropdown
              v-model="form.status"
              :options="statuses"
              dataKey="value"
              optionLabel="label"
              placeholder="Select Status"
            />
          </div>
        </div>
      </Panel>
      <div class="p-d-flex p-jc-end">
        <Button icon="pi pi-check" label="Submit" />
      </div>
    </form>
  </AdminForm>
</template>

<script setup>
import { onMounted, reactive, ref } from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useRoute, useRouter } from "vue-router";
import AdminForm from "@/components/AdminForm.vue";
import Panel from "primevue/panel";
import Dropdown from "primevue/dropdown";
import Button from "primevue/button";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const fetchDataStore = useFetchDataStore();
const router = useRouter();
const route = useRoute();
const form = reactive({
  user_person_code: null,
  instructor_id: null,
  instructor_availability_id: null,
  status: null,
});

const users = ref([]);
const instructors = ref([]);
const availabilities = ref([]);
const statuses = ref([
  { label: $t(`reservations.submitted`), value: "submitted" },
  { label: $t(`reservations.accepted`), value: "accepted" },
  { label: $t(`reservations.denied`), value: "denied" },
]);

const submitForm = async () => {
  await fetchDataStore.updateInstance("reservations", form, route.params.id);
  router.push({ name: "ReservationList" });
};

onMounted(async () => {
  const reservation = await fetchDataStore.fetchInstance(
    "reservations",
    route.params.id
  );
  Object.assign(form, reservation);

  users.value = await fetchDataStore.fetchDatabaseData("users");
  instructors.value = await fetchDataStore.fetchDatabaseData("instructors");
  availabilities.value = await fetchDataStore.fetchDatabaseData(
    "InstructorAvailability"
  );
});
</script>

<style scoped>
.p-fluid > .p-field {
  margin-bottom: 1rem;
}
</style>
