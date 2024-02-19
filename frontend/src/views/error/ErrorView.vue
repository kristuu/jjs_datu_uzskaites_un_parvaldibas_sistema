<script setup>
import { computed } from "vue";
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const router = useRouter();

const errorStatus = computed(() => store.state.errorStatus);
const errorMessage = computed(() => store.state.errorMessage);

const goBack = () => {
  router.go(-2);
}
</script>

<template>
  <div class="w-100 h-100 top-0 start-0 position-absolute bg-body-secondary">
    <div class="w-100 h-100 d-flex flex-column justify-content-center position-absolute top-0 align-items-center">
      <img class="position-absolute top-0 p-3" src="@/assets/logo-red.svg"/>
        <div class="p-3">
          <a href="#" @click="goBack"><span class="bi bi-arrow-return-left"> Atgriezties</span></a>
          <h1 class="fw-bold display-1 mt-5">{{ errorStatus }}</h1>
          <h1 class="fw-bold display-6">{{ errorMessage }}</h1>
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