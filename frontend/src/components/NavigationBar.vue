<script>
import { mapState, mapActions } from "vuex";

export default {
  computed: {
    // using Vuex helpers to map 'authorized' state to a computed property
    ...mapState(['authorized'])
  },
  methods: {
    // Vuex helper usage to map 'logout' action to a method
    ...mapActions(['logout'])
  }
}
</script>

<template>
  <nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light" aria-label="Galvenā navigācija">
    <div class="container-fluid">
      <a class="navbar-brand" href="/"><img src="../assets/logo.svg" height="45" /></a>
      <button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse" aria-label="Navigācijas sadaļas atvēršana/aizvēršana">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-collapse offcanvas-collapse" id="navbar-main">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <router-link class="nav-link active" aria-current="page" to="/">Sākums</router-link>
          </li>
          <li class="nav-item" v-if="authorized">
            <router-link class="nav-link" to="/users">Lietotāji</router-link>
          </li>
          <li class="nav-item" v-if="!authorized">
            <router-link class="nav-link" to="/login">Autorizēties</router-link>
          </li>
          <li class="nav-item" v-if="!authorized">
            <router-link class="nav-link" to="/register">Reģistrēties</router-link>
          </li>
          <li class="nav-item" @click="logout" v-if="authorized">Beigt sesiju</li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="nav-scroller bg-body shadow-sm">
    <nav class="nav">
      <router-link class="nav-link" to="#">Testa režīms</router-link>
    </nav>
  </div>
</template>

<style scoped>

</style>