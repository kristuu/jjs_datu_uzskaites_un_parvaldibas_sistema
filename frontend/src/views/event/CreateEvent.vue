<template>
  <AdminForm
    :database-table="`events`"
    :model-name="`Event`"
    :page-name="$t(`pageHeadings.events.events`)"
    :short-desc="$t(`pageHeadings.events.create event`)"
  >
    <form class="row text-start needs-validation">
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="name">{{ $t(`fields.event.name`) }}</label>
          <InputText
            id="name"
            v-model="instance.name"
            :invalid="errorList.name"
          />
          <InputError :errors="errorList.name" />
        </div>
      </div>
      <div class="col-12 sm:col-6">
        <div class="flex flex-column gap-1">
          <label for="start">{{ $t(`fields.event.start`) }}</label>
          <Calendar
            id="start"
            v-model="instance.start"
            :invalid="errorList.start"
            showButtonBar
            showTime
          />
          <InputError :errors="errorList.start" />
        </div>
      </div>
      <div class="col-12 sm:col-6">
        <div class="flex flex-column gap-1">
          <label for="end">{{ $t(`fields.event.end`) }}</label>
          <Calendar
            id="end"
            v-model="instance.end"
            :invalid="errorList.end"
            :minDate="instance.start"
            showButtonBar
            showTime
          />
          <InputError :errors="errorList.end" />
        </div>
      </div>
      <div class="col-12 sm:col-6 md:col-4">
        <div class="flex flex-column gap-1">
          <label for="event_type">{{ $t(`fields.event.type`) }}</label>
          <Dropdown
            id="event_type"
            v-model="instance.event_type"
            :invalid="errorList.event_type_id"
            :options="eventTypesList"
            optionLabel="name"
            @update:modelValue="
              (value) => {
                instance.event_type_id = value.id;
              }
            "
          />
          <InputError :errors="errorList.event_type_id" />
        </div>
      </div>
      <div class="col-12 sm:col-6 md:col-4">
        <div class="flex flex-column gap-1">
          <label for="event_category">{{ $t(`fields.event.category`) }}</label>
          <Dropdown
            id="event_category"
            v-model="instance.event_category"
            :invalid="errorList.event_category_id"
            :options="eventCategoriesList"
            optionLabel="name"
            @update:modelValue="
              (value) => {
                instance.event_category_id = value.id;
              }
            "
          />
          <InputError :errors="errorList.event_category_id" />
        </div>
      </div>
      <div class="col-12 md:col-4">
        <div class="flex flex-column gap-1">
          <label for="event_category">{{ $t(`fields.event.location`) }}</label>
          <Dropdown
            id="event_category"
            v-model="instance.location"
            :invalid="errorList.location_id"
            :options="locationsList"
            optionLabel="name"
            @update:modelValue="
              (value) => {
                instance.location_id = value.id;
              }
            "
          />
          <InputError :errors="errorList.location_id" />
        </div>
      </div>
      <div class="col-12">
        <div class="flex flex-column gap-1">
          <label for="comment">{{ $t(`fields.event.comment`) }}</label>
          <Editor
            id="comment"
            v-model="instance.comment"
            editorStyle="height: 10rem;"
          >
            <template v-slot:toolbar>
              <span class="ql-formats">
                <button
                  v-tooltip.bottom="$t(`editor.bold`)"
                  class="ql-bold"
                ></button>
                <button
                  v-tooltip.bottom="$t(`editor.italic`)"
                  class="ql-italic"
                ></button>
                <button
                  v-tooltip.bottom="$t(`editor.underline`)"
                  class="ql-underline"
                ></button>
              </span>
              <span class="ql-formats">
                <button
                  v-tooltip.bottom="$t(`editor.superscript`)"
                  class="ql-script"
                  value="super"
                ></button>
                <button
                  v-tooltip.bottom="$t(`editor.subscript`)"
                  class="ql-script"
                  value="sub"
                ></button>
              </span>
              <span class="ql-formats">
                <select
                  v-tooltip.bottom="$t(`editor.text_color`)"
                  class="ql-color"
                ></select>
                <select
                  v-tooltip.bottom="$t(`editor.text_background`)"
                  class="ql-background"
                ></select>
              </span>
              <span class="ql-formats">
                <button class="ql-list" value="ordered"></button>
                <button class="ql-list" value="bullet"></button>
                <button class="ql-indent" value="-1"></button>
                <button class="ql-indent" value="+1"></button>
              </span>
            </template>
          </Editor>
        </div>
      </div>
    </form>
  </AdminForm>
</template>

<script setup>
import { computed, onBeforeMount, ref } from "vue";
import { useFetchDataStore } from "@/stores/fetchDataStore";
import { useErrorStore } from "@/stores/errorStore";
import axios from "@/services/axios";

import AdminForm from "@/components/AdminForm.vue";
import InputError from "@/components/error/inputError.vue";

const fetchDataStore = useFetchDataStore();
let instance = computed(() => fetchDataStore.instance);
const errorStore = useErrorStore();

let errorList = computed(() => errorStore.errorList);

let eventCategoriesList = ref();
let eventTypesList = ref();
let locationsList = ref();

onBeforeMount(async () => {
  try {
    const response_ec = await axios.get(`/api/event/categories`);
    eventCategoriesList.value = response_ec.data.instances;
  } catch (error) {
    console.error(error);
  }

  try {
    const response_et = await axios.get(`/api/event/types`);
    eventTypesList.value = response_et.data.instances;
  } catch (error) {
    console.error(error);
  }

  try {
    const response_loc = await axios.get(`/api/event/locations`);
    locationsList.value = response_loc.data.instances;
  } catch (error) {
    console.error(error);
  }
});
</script>

<style scoped></style>
