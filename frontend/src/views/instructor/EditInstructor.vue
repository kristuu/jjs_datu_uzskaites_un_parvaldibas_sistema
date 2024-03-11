<template>
  <AdminForm v-if="can('edit instances') && can('manage instructors')"
             :page-name="$t(`pageHeadings.instructors.instructors`)"
             :short-desc="$t(`pageHeadings.instructors.edit instructor`)"
             :model-name="`Instructor`"
             :database-table="`instructors`">
    <form id="editUserForm" class="row gap-1 py-1 text-start needs-validation">
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
              <small class="secondary"> Lietotāju šajā skatā rediģēt nav iespējams</small>
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
        <Panel header="Sertifikāts">
          <div class="row">
            <div class="col-12" v-if="instance.certificate">
              <div class="flex flex-column gap-1">
                <label>{{ $t(`table.id`) }}</label>
                <Dropdown v-model="instance.certificate"
                          :options="certificateList"
                           :invalid="errorList.certificate?.id"/>
                <InputError :errors="errorList.certificate?.id" />
              </div>
            </div>
          </div>
        </Panel>
      </div>
    </form>
  </AdminForm>
</template>

<script setup>
import {computed, ref} from "vue";
import axios from "@/services/axios";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";

const fetchDataStore = useFetchDataStore();
let instance = computed(() => fetchDataStore.instance);
const errorStore = useErrorStore();

let errorList = computed(() => errorStore.errorList);

let categoryList = ref();
let certificateList = ref();

axios.get(`/categories`)
    .then(response => {
      categoryList.value = response.data.instances;
    })
    .catch(error => {
      console.error(error);
    })

axios.get(`/unused_certificates`)
    .then(response => {
      certificateList.value = response.data.instances;
    })
    .catch(error => {
      console.error(error);
    })
</script>

<style scoped>
</style>