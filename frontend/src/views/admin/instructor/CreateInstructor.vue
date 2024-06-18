<template>
  <AdminForm
    v-if="can('edit instances') && can('manage instructors')"
    :database-table="`instructors`"
    :model-name="`Instructor`"
    :page-name="$t(`pageHeadings.instructors.instructors`)"
    :short-desc="$t(`pageHeadings.instructors.create instructor`)"
  >
    <form id="editUserForm" class="row gap-1 text-start needs-validation">
      <div class="col-12">
        <Panel header="Lietotājs">
          <div class="row">
            <div class="col-12">
              <div class="flex flex-column gap-1">
                <label>{{ $t(`table.instructors.choose_user`) }}</label>
                <Dropdown
                  v-model="instance.user"
                  :invalid="errorList.user_person_code"
                  :optionLabel="displayUser"
                  :options="userList"
                  filter
                  resetFilterOnHide
                  @update:modelValue="
                    (value) => {
                      instance.user_person_code = value.person_code;
                    }
                  "
                />
                <InputError :errors="errorList.user_person_code" />
              </div>
            </div>
            <div class="col-12">
              <Panel header="Izvēlēts:" toggleable>
                <div class="row">
                  <div class="col-12">
                    <div class="flex flex-column gap-1">
                      <label for="name">{{ $t(`table.users.name`) }}</label>
                      <InputText :modelValue="instance.user?.name" disabled />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="flex flex-column gap-1">
                      <label for="name">{{ $t(`table.users.surname`) }}</label>
                      <InputText
                        :modelValue="instance.user?.surname"
                        disabled
                      />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="flex flex-column gap-1">
                      <label for="name">{{
                        $t(`table.users.person_code`)
                      }}</label>
                      <InputMask
                        :modelValue="instance.user?.person_code"
                        disabled
                        mask="999999-99999"
                      />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="flex flex-column gap-1">
                      <label for="name">{{
                        $t(`table.users.birthdate`)
                      }}</label>
                      <Calendar
                        :modelValue="instance.user?.birthdate"
                        disabled
                      />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="flex flex-column gap-1">
                      <label for="name">{{ $t(`table.users.phone`) }}</label>
                      <InputText :modelValue="instance.user?.phone" disabled />
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="flex flex-column gap-1">
                      <label for="name">{{ $t(`table.users.email`) }}</label>
                      <InputText :modelValue="instance.user?.email" disabled />
                    </div>
                  </div>
                </div>
              </Panel>
            </div>
          </div>
          <template #footer>
            <div class="d-flex align-items-center justify-content-center gap-1">
              <i class="bi bi-exclamation-circle-fill" />
              <small class="secondary">
                {{ $t(`user_details_not_editable`) }}</small
              >
            </div>
          </template>
        </Panel>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label>{{ $t(`table.instructors.job_start_date`) }}</label>
          <Calendar
            v-model="instance.job_start_date"
            :invalid="errorList.job_start_date"
            dateFormat="dd.mm.yy"
          />
          <InputError :errors="errorList.job_start_date" />
        </div>
      </div>
      <div class="col-12">
        <label>{{ $t(`table.instructors.short_description`) }}</label>
        <Textarea
          v-model="instance.short_description"
          :invalid="errorList.short_description"
          autoResize
          rows="3"
          style="width: 100%; max-width: 100%"
        />
        <small class="text-secondary"
          ><i class="bi bi-info-circle me-1" />{{
            $t(`table.info.max_characters`, { total: 100 })
          }}</small
        >
        <InputError :errors="errorList.short_description" />
      </div>
      <div class="col-12">
        <label>{{ $t(`table.instructors.description`) }}</label>
        <Textarea
          v-model="instance.description"
          :invalid="errorList.description"
          autoResize
          rows="6"
          style="width: 100%; max-width: 100%"
        />
        <InputError :errors="errorList.description" />
      </div>
      <div class="col-12">
        <Panel header="Sertifikāts">
          <div class="row">
            <div class="col-12">
              <div class="flex flex-column gap-1">
                <Dropdown
                  v-model="instance.certificate"
                  :invalid="errorList.certificate?.id"
                  :optionLabel="displayCertificate"
                  :options="certificateList"
                  filter
                  resetFilterOnHide
                  @update:modelValue="
                    (value) => {
                      instance.certificate_id = value.id;
                    }
                  "
                />
                <InputError :errors="errorList.certificate?.id" />
              </div>
            </div>
            <div class="col-12">
              <Panel header="Izvēlēts:" toggleable>
                <div class="row">
                  <div class="d-flex flex-column md:col-4 col-12">
                    <label>{{ $t(`table.id`) }}</label>
                    <span>{{ instance.certificate?.id ?? `-` }}</span>
                  </div>
                  <div class="d-flex flex-column md:col-4 col-12">
                    <label>{{ $t(`table.instructors.categories.name`) }}</label>
                    <span>{{
                      instance.certificate?.category.name ?? `-`
                    }}</span>
                  </div>
                  <div class="d-flex flex-column md:col-4 col-12">
                    <label>{{
                      $t(`table.instructors.certificates.expiration_date`)
                    }}</label>
                    <span>{{
                      instance.certificate?.expiration_date ?? `-`
                    }}</span>
                  </div>
                </div>
              </Panel>
            </div>
          </div>
        </Panel>
      </div>
    </form>
  </AdminForm>
</template>

<script setup>
import { computed, ref } from "vue";
import axios from "@/services/axios";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";
import { useRoute } from "vue-router";
import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";

const route = useRoute();

const fetchDataStore = useFetchDataStore();
let instance = computed(() => fetchDataStore.instance);
const errorStore = useErrorStore();

let errorList = computed(() => errorStore.errorList);

let userList = ref();
let certificateList = ref();

axios
  .get(`/api/users`)
  .then((response) => {
    userList.value = response.data.instances;
  })
  .catch((error) => {
    console.error(error);
  });

axios
  .get(`/api/unused_certificates/${route.params.id}`)
  .then((response) => {
    certificateList.value = response.data;
    console.log(`INSTANCE ID: ${route.params.id}`);
    console.log(certificateList.value);
  })
  .catch((error) => {
    console.error(error);
  });

const displayCertificate = (certificate) =>
  `${certificate.id} | ${certificate.category.name}`;
const displayUser = (user) =>
  `${user.person_code.slice(0, 6)}-${user.person_code.slice(6, 11)} | ${
    user.name
  } ${user.surname}`;
</script>

<style scoped></style>
