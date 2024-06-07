<template>
  <AdminForm
    v-if="can('edit instances') && can('manage locations')"
    :database-table="`locations`"
    :model-name="`Location`"
    :page-name="$t(`pageHeadings.locations.locations`)"
    :short-desc="$t(`pageHeadings.locations.edit location`)"
  >
    <form class="row text-start needs-validation">
      <div class="col-12 md:col-8">
        <div class="flex flex-column gap-1">
          <label for="name">{{ $t(`fields.locations.name`) }}</label>
          <InputText
            id="name"
            v-model="instance.name"
            :invalid="errorList.name"
          />
          <InputError :errors="errorList.name" />
        </div>
      </div>
      <div class="col-12 md:col-2">
        <div class="flex flex-column gap-1">
          <label for="stables">{{ $t(`fields.locations.stables`) }}</label>
          <ToggleButton
            id="stables"
            v-model="instance.stables"
            :invalid="errorList.stables"
            :offLabel="$t(`no`)"
            :onLabel="$t(`yes`)"
            offIcon="pi pi-times"
            onIcon="pi pi-check"
          ></ToggleButton>
        </div>
      </div>
      <div class="col-12 md:col-2">
        <div class="flex flex-column gap-1">
          <label for="stables">{{ $t(`fields.locations.competitions`) }}</label>
          <ToggleButton
            id="stables"
            v-model="instance.competitions"
            :change="!instance.stables ? (instance.horse_capacity = 0) : null"
            :invalid="errorList.competitions"
            :offLabel="$t(`no`)"
            :onLabel="$t(`yes`)"
            offIcon="pi pi-times"
            onIcon="pi pi-check"
          ></ToggleButton>
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="horse_capacity">{{
            $t(`fields.locations.horse_capacity`)
          }}</label>
          <InputText
            v-model.number="instance.horse_capacity"
            :disabled="!instance.stables"
            class="w-full mb-2"
          />
          <Slider
            v-model="instance.horse_capacity"
            :disabled="!instance.stables"
            class="mx-2"
          />
        </div>
      </div>
    </form>
  </AdminForm>
</template>

<script setup>
import { computed } from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";

const fetchDataStore = useFetchDataStore();
let instance = computed(() => fetchDataStore.instance);
const errorStore = useErrorStore();

let errorList = computed(() => errorStore.errorList);
</script>

<style scoped></style>
