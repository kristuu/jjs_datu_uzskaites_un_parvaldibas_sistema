<script setup>
import {computed, ref} from "vue";
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const router = useRouter();

const errorStatus = computed(() => store.state.errorStatus);
const errorMessage = computed(() => store.state.errorMessage);

const localErrorStatus = ref(errorStatus.value || 404);
const localErrorMessage = ref(errorMessage.value || 'Resource not found');

const goBack = () => {
  router.go(-2);
}
</script>

<template>
  <div class="w-full h-full top-0 left-0 absolute surface-200">
    <div class="w-full h-full flex flex-column justify-content-center absolute top-0 align-items-center">
      <img class="absolute top-0 p-3" src="@/assets/logo-red.svg"/>
        <div class="p-3">
          <a href="#" @click="goBack"><span class="bi bi-arrow-return-left"> Atgriezties</span></a>
          <h1 class="fw-bold display-1 mt-5">{{ localErrorStatus }}</h1>
          <h1 class="fw-bold display-6">{{ localErrorMessage }}</h1>
          <span class="text-muted">Ja esi pārliecināts, ka šī ir sistēmas kļūda, sazinies ar lapas administrāciju</span>
        </div>
    </div>
  </div>
</template>

<style scoped>
h1 {
  color: #8b2922;
}

a {
  text-decoration: none;
  color: #7b251e;
  z-index: 100000000;
}

img {
  max-width: 7rem;
}

.bi {
  color: #7b251e;
}
</style>