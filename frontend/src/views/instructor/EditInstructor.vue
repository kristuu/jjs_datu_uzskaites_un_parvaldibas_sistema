<template>
  <AdminForm v-if="can('edit instances') && can('manage instructors')"
             :page-name="$t(`pageHeadings.instructors.instructors`)"
             :short-desc="$t(`pageHeadings.instructors.edit instructor`)"
             :model-name="`Instructor`"
             :database-table="`instructors`">
    <form id="editUserForm" class="row gap-1 text-start needs-validation">
      <div class="col-12">
        <Panel header="Lietotājs" toggleable>
          <div class="row">
            <div class="col-12">
              <div class="flex flex-column gap-1">
                <label for="name">{{ $t(`table.users.name`) }}</label>
                <InputText :modelValue="instance.user?.name"
                           disabled/>
              </div>
            </div>
            <div class="col-12">
              <div class="flex flex-column gap-1">
                <label for="name">{{ $t(`table.users.surname`) }}</label>
                <InputText :modelValue="instance.user?.surname"
                           disabled/>
              </div>
            </div>
            <div class="col-12">
              <div class="flex flex-column gap-1">
                <label for="name">{{ $t(`table.users.person_code`) }}</label>
                <InputMask :modelValue="instance.user?.person_code"
                           mask="999999-99999"
                           disabled/>
              </div>
            </div>
            <div class="col-12">
              <div class="flex flex-column gap-1">
                <label for="name">{{ $t(`table.users.birthdate`) }}</label>
                <Calendar :modelValue="instance.user?.birthdate"
                           disabled/>
              </div>
            </div>
            <div class="col-12">
              <div class="flex flex-column gap-1">
                <label for="name">{{ $t(`table.users.phone`) }}</label>
                <InputText :modelValue="instance.user?.phone"
                           disabled/>
              </div>
            </div>
            <div class="col-12">
              <div class="flex flex-column gap-1">
                <label for="name">{{ $t(`table.users.email`) }}</label>
                <InputText :modelValue="instance.user?.email"
                           disabled/>
              </div>
            </div>
          </div>
          <template #footer>
            <div class="d-flex align-items-center justify-content-center gap-1">
              <i class="bi bi-exclamation-circle-fill" />
              <small class="secondary"> {{ $t(`user_details_not_editable`) }}</small>
            </div>
          </template>
        </Panel>
      </div>
      <div class="col-12" v-if="instance.job_start_date">
        <div class="flex flex-column gap-1">
          <label>{{ $t(`table.instructors.job_start_date`) }}</label>
          <Calendar v-model="instance.job_start_date"
                    dateFormat="dd.mm.yy"
                     :invalid="errorList.job_start_date"/>
          <InputError :errors="errorList.job_start_date" />
        </div>
      </div>
      <div class="col-12">
        <label>{{ $t(`table.instructors.short_description`) }}</label>
        <Textarea
            v-model="instance.short_description"
            rows="3" autoResize
            style="width: 100%; max-width: 100%;"
            :invalid="errorList.short_description" />
        <InputError :errors="errorList.short_description" />
      </div>
      <div class="col-12">
        <label>{{ $t(`table.instructors.description`) }}</label>
        <Textarea
            v-model="instance.description"
            rows="6" autoResize
            style="width: 100%; max-width: 100%;"
            :invalid="errorList.description" />
        <InputError :errors="errorList.description" />
      </div>
      <div class="col-12" v-if="instance.certificate">
        <Panel header="Sertifikāts">
          <div class="row">
            <div class="col-12">
              <div class="flex flex-column gap-1">
                <Dropdown v-model="instance.certificate"
                          @update:modelValue="value => { instance.certificate_id = value.id }"
                          :options="certificateList" filter resetFilterOnHide
                          :optionLabel="displayCertificate"
                          :invalid="errorList.certificate?.id"/>
                <InputError :errors="errorList.certificate?.id" />
              </div>
            </div>
            <div class="col-12">
              <Panel header="Izvēlēts:" toggleable>
                <div class="row">
                  <div class="d-flex flex-column col-md-4 col-12">
                    <label>{{ $t(`table.id`) }}</label>
                    <span>{{ instance.certificate.id }}</span>
                  </div>
                  <div class="d-flex flex-column col-md-4 col-12">
                    <label>{{ $t(`table.instructors.categories.name`) }}</label>
                    <span>{{ instance.certificate.category.name }}</span>
                  </div>
                  <div class="d-flex flex-column col-md-4 col-12">
                    <label>{{ $t(`table.instructors.certificates.expiration_date`) }}</label>
                    <span>{{ instance.certificate.expiration_date }}</span>
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
import {computed, onMounted, ref} from "vue";
import axios from "@/services/axios";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";
import { useRoute } from "vue-router";

const route = useRoute();

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";

const fetchDataStore = useFetchDataStore();
let instance = computed(() => fetchDataStore.instance);
const errorStore = useErrorStore();

let errorList = computed(() => errorStore.errorList);

let categoryList = ref();
let certificateList = ref();

axios.get(`/api/unused_certificates/${route.params.id}`)
    .then(response => {
      certificateList.value = response.data;
      console.log(`INSTANCE ID: ${route.params.id}`)
      console.log(certificateList.value);
    })
    .catch(error => {
      console.error(error);
    });

const displayCertificate = (certificate) => `${certificate.id} | ${certificate.category.name}`;
</script>

<style scoped>
</style>