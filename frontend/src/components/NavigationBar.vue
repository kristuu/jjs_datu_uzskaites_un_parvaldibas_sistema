<script>
import axios from "@/services/axios";
import { mapState, mapActions } from "vuex";

export default {
  data() {
    return {
      user: null,
    }
  },
  computed: {
    // using Vuex helpers to map 'authorized' state to a computed property
    ...mapState(['authorized'])
  },
  methods: {
    // Vuex helper usage to map 'logout' action to a method
    ...mapActions(['logout']),
  },
  async created() {
    if(this.authorized) {
      try {
        const response = await axios.get('/user');
        this.user = response.data;
      } catch(error) {
        if (error.response.status === 401) {
          this.user = null;
        } else {
          console.error(error.response.status);
        }
      }
    }
  }
}
</script>

<template>
  <header class="navbar-expand-lg top">
    <div class="collapse navbar-collapse" id="navbar-main">
      <div class="d-print-none navbar navbar-expand-lg navbar-dark navbar-overlap yrs-bg-primary">
        <div class="container-xl">
          <ul class="navbar-nav">
            <li class="nav-brand">
              <a class="navbar-brand" href="/"><img src="../assets/logo-white.svg" style="width: 50px; height: auto;"/></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/users">
                <i class="bi bi-person"></i>
                <span>Lietotāji</span>
              </a>
            </li>
          </ul>
          <ul class="nav navbar-nav d-md-down-none"></ul>
          <ul class="nav navbar-nav d-flex flex-row flex-shrink-0">
            <li class="nav-item me-2 dropdown">
              <a class="nav-link dropdown-toggle text-decoration-none"
                 data-bs-toggle="dropdown"
                 data-toggle="dropdown"
                 data-bs-auto-close="true"
                 role="button"
                 aria-expanded="false"
                 style="cursor: pointer">
                <i class="bi bi-translate"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item active">
                    <span>Latviešu</span>
                  </a>
                </li>
              </ul>
            </li>
            <div class="nav-item dropdown">
              <a href="#" class="nav-link d-flex lh-1 text-end p-0"
                 data-bs-toggle="dropdown" aria-expanded="false">
                <div class="d-none d-xl-block pe-2">
                  <div class="fw-bold">{{ user ? user.name : '' }}</div>
                  <div class="mt-1 small">Admin</div>
                </div>
                <span class="bg-white avatar fw-bold text-black-50">
                  {{ user ? user.name[0].toUpperCase() + user.surname[0].toUpperCase() : "" }}
                </span>
              </a>
              <div class="dropdown-menu dropdown-menu-end">
                <a href="#" class="dropdown-item">
                  <i class="bi bi-person-gear"></i>
                  <span>Mans profils</span>
                </a>
                <div class="dropdown-divider"></div>
                <a @click="logout" class="dropdown-item">
                  <i class="bi bi-lock"></i>
                  <span>Beigt sesiju</span>
                </a>
              </div>
            </div>
          </ul>
        </div>
      </div>
    </div>
  </header>
  <aside class="navbar navbar-expand-lg navbar-dark d-block d-lg-none yrs-bg-primary">
    <div class="container-fluid">
      <ul class="nav navbar-nav d-flex flex-row align-items-center justify-content-between w-100">
        <button class="navbar-toggler p-0 collapsed"
                type="button" data-bs-toggle="collapse"
                data-bs-target="#mobileMainNav" aria-controls="mobileMainNav"
                aria-expanded="false" aria-label="Toggle main navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="d-flex flex-row align-items-center">
          <li class="nav-item me-2 dropdown">
            <a class="nav-link dropdown-toggle text-decoration-none"
               data-bs-toggle="dropdown"
               data-toggle="dropdown"
               data-bs-auto-close="outside"
               role="button"
               aria-expanded="false"
               style="cursor: pointer">
              <i class="bi bi-translate"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item active">
                  <span>Latviešu</span>
                </a>
              </li>
            </ul>
          </li>
          <div class="nav-item dropdown">
            <a href="#" class="nav-link d-flex lh-1 text-reset p-0"
               data-bs-toggle="dropdown" aria-label="Open authorized user menu"
               aria-expanded="false">
              <span class="bg-white avatar fw-bold text-black-50">
                {{ user ? user.name[0].toUpperCase() + user.surname[0].toUpperCase() : "" }}
              </span>
              <div class="d-none d-xl-block ps-2">
                <div>{{ user ? user.name : "" }}</div>
              </div>
            </a>
          </div>
        </div>
      </ul>
    </div>
  </aside>
</template>

<style scoped>
.navbar .dropdown-menu {
  z-index: 1050;
  position: absolute;
}

.navbar {
  position: relative;
}

.navbar-overlap:after {
  height: 13rem;
  content: "";
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: inherit;
  z-index: -1;
  box-shadow: inherit;
}

.avatar {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  height: 35px;
  width: 35px;
  border-radius: 50%;
  font-size: 0.75em;
}
</style>