<script>
import { mapState, mapActions } from "vuex";
import {Offcanvas} from 'bootstrap';

export default {
  data() {
    return {
      offcanvasNavbar: null,
    }
  },
  computed: {
    // using Vuex helpers to map 'authorized' state to a computed property
    ...mapState(['authorized'])
  },
  methods: {
    // Vuex helper usage to map 'logout' action to a method
    ...mapActions(['logout']),
    hideNavbar() {
      this.offcanvasNavbar.hide();
    },
    logoutAndHideNavbar() {
      this.logout();
      this.hideNavbar();
    },
  },
  mounted() {
    if (!this.editModal) {
      this.$nextTick(() => {
        this.offcanvasNavbar = new Offcanvas(document.getElementById('navbarMain'));
        this.offcanvasNavbar.hide();
      })
    } else {
      this.offcanvasNavbar.hide();
    }
  },
}
</script>

<template>
  <nav class="navbar navbar-expand-lg fixed-top navbar-dark yrs-bg-primary" aria-label="Galvenā navigācija">
    <div class="container-xxl">
      <a class="navbar-brand" href="/"><img src="../assets/logo-white.svg" height="45" /></a>
      <button class="navbar-toggler p-0 border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-label="Navigācijas sadaļas atvēršana/aizvēršana">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-collapse offcanvas offcanvas-end" tabindex="-1" id="navbarMain">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title">Jauno Jātnieku skola</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <ul class="navbar-nav d-flex w-100 mb-2 mb-lg-0">
          <li class="nav-item h-100">
            <router-link class="nav-link" aria-current="page" to="/"
                         @click="hideNavbar">Sākums</router-link>
          </li>
          <li class="nav-item" v-if="authorized">
            <router-link class="nav-link" to="/users"
                         @click="hideNavbar">Lietotāji</router-link>
          </li>
          <li class="nav-item" v-if="authorized">
            <router-link class="nav-link" to="/booking"
                         @click="hideNavbar">Treneri</router-link>
          </li>
          <li class="nav-item ms-lg-auto mt-5 mt-lg-auto" v-if="!authorized">
            <router-link class="nav-link" to="/login"
                         @click="hideNavbar">Autorizēties</router-link>
          </li>
          <li class="nav-item" v-if="!authorized">
            <router-link class="nav-link" to="/register"
                         @click="hideNavbar">Reģistrēties</router-link>
          </li>
          <li class="nav-item ms-lg-auto mt-5 mt-lg-0" v-if="authorized">
            <a to="" class="nav-link" @click="logoutAndHideNavbar">Beigt sesiju</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.navbar-nav .nav-item .nav-link {
  display: flex;
  align-items: center;
  padding: 10px 15px;
  line-height: normal;
}

.navbar-nav .nav-item .nav-link:hover {
  background-color: white;
  color: var(--yrs-primary);
}

@media (max-width: 992px) {
  .navbar-nav .nav-item .nav-link {
    color: var(--yrs-primary);
  }

  .navbar-nav .nav-item .nav-link:hover {
    background-color: var(--yrs-primary);
    color: white;
  }
}
</style>