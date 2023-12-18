<script>
import axios from '@/services/axios';
import { Modal } from 'bootstrap';

export default {
  data() {
    return {
      users: [],
      currentPage: 1,
      totalPages: 0,
      totalUsers: 0,
      perPage: 10,
      isLoading: false,
      toggleDetails: {},
      user: {},
      createModal: null,
      selectedUser: {},
      editModal: null,
    };
  },
  methods: {
    async fetchUsers() {
      this.isLoading = true;
      try {
        const response = await axios.get(`/users?page=${this.currentPage}&perPage=${this.perPage}`);
        console.log(response);

        // Fetch users and total pages as per the response
        this.users = response.data.message.data;

        this.totalPages = response.data.message.last_page;
        this.totalUsers = response.data.message.total;
        this.isLoading = false;
      } catch (e) {
        console.error('Error fetching user list: ', e);
        this.isLoading = false;
      }
    },
    openEditModal(user) {
      this.selectedUser = { ...user };
      if (!this.editModal) {
        this.$nextTick(() => {
          this.editModal = new Modal(document.getElementById('editUserModal'));
          this.editModal.show();
        })
      } else {
        this.editModal.show();
      }
    },
    openCreateModal() {
      if (!this.createModal) {
        this.$nextTick(() => {
          this.createModal = new Modal(document.getElementById('createUserModal'));
          this.createModal.show();
        })
      } else {
        this.createModal.show();
      }
    },
    async createUser() {
      try {
        await axios.post('/users', this.user);
        this.createModal.hide();
        await this.fetchUsers();
      } catch (e) {
        if (e.response) {
          if (e.response.status === 422) {
            this.errorList = e.response.data.errors;
          }
        } else if (e.request) {
          console.log(e.request);
        } else {
          console.log('Unrecognized error: ', e.message);
        }
      }
    },
    async updateUser() {
      try {
        await axios.put(`/user/${this.selectedUser.person_code}`, this.selectedUser);
        this.editModal.hide();
        await this.fetchUsers();
      } catch (e) {
        this.errorList = e.response.data.errors;
        console.log(e);
      }
    },
    async deleteUser(person_code) {
      try {
        await axios.delete(`/user/${person_code}`);
        await this.fetchUsers();
      } catch (e) {
        this.errorList = e.response.data.errors;
        console.log(e);
      }
    }
  },
  created() {
    this.fetchUsers();
  },
  watch: {
    currentPage() {
      this.fetchUsers();
    },
  },
}
</script>

<template>
  <div class="container-xl">
    <h2 class="fw-bold mb-5">Lietotāju pārvaldība</h2>
    <div class="d-flex justify-content-end">
      <button class="btn btn-primary mb-3" @click="openCreateModal">Jauns lietotājs</button>
    </div>
    <div class="table-responsive">
      <table class="table small table-striped table-hover">
        <caption>Lapa {{ this.currentPage }} no {{ this.totalPages }}</caption>
        <thead>
          <th scope="col">Personas kods</th>
          <th scope="col">Vārds (-i)</th>
          <th scope="col">Uzvārds (-i)</th>
          <th scope="col">E-pasts</th>
          <th scope="col">Tel. nr.</th>
        </thead>
        <tbody class="table-group-divider">
          <template v-if="isLoading">
            <tr>
              <td colspan="8">Atgūst informāciju...</td>
            </tr>
          </template>
          <template v-else v-for="(user, index) in users" :key="user.person_code">
            <tr>
              <td>{{ user.person_code }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.surname }}</td>
              <td>{{ user.email }}</td>
              <td>{{ user.phone }}</td>
              <td>
                <button class="btn btn-sm btn-primary me-2" type="button"
                        @click="toggleDetails[index] = !toggleDetails[index]" data-bs-toggle="collapse"
                        :data-bs-target="'#userdetails-' + user.person_code" aria-expanded="false"
                        :aria-controls="'userdetails-' + user.person_code">
                  Detaļas
                </button>
                <button class="btn btn-sm btn-warning me-2" @click="openEditModal(user)"
                        data-toggle="modal" data-target="#editUserModal">
                  Rediģēt
                </button>
                <button class="btn btn-sm btn-danger"
                   @click="deleteUser(user.person_code)">
                  Dzēst
                </button>
              </td>
            </tr>
            <tr>
              <td :class="{ 'p-0': !toggleDetails[index] }" colspan="8">
                <div :id="'userdetails-' + user.person_code" class="collapse text-start">
                  <p><strong>Dzimšanas datums: </strong> {{ user.birthdate }}</p>
                  <p><strong>Bankas konta nr.: </strong> {{ user.iban_code }}</p>
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>

<!--      Pagination-->
      <nav aria-label="Tabulas navigācija" v-if="this.totalUsers > 1">
        <ul class="pagination">
          <li class="page-item">
            <button class="btn btn-primary"
                    @click="currentPage > 1 ? currentPage-- : null"
                    :disabled="currentPage === 1">
              Iepriekšējā
            </button>
          </li>
          <li class="page-item">
            <button class="btn btn-primary"
                    @click="currentPage < totalPages ? currentPage++ : null"
                    :disabled="currentPage === totalPages">
              Nākamā
            </button>
          </li>
        </ul>
        <div>
          <label class="me-1">Ieraksti lapā:</label>
          <select v-model="perPage" @change="fetchUsers">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
          </select>
        </div>
      </nav>
    </div>
  </div>

<!--  Pop-up modal for creating users -->
  <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <a class="navbar-brand" href="/"><img src="../../assets/logo-red.svg" height="45" /></a>
          <h5 class="modal-title mx-auto" id="exampleModalLabel">Lietotāja pievienošana</h5>
          <button type="button" class="btn-close" data-dismiss="modal" @click="this.createModal.hide()" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="createUser" class="row g-3 text-start">
            <div class="col-lg-4">
              <label for="name" class="form-label">Vārds (-i)</label>
              <input v-model="user.name" type="text" class="form-control" id="name">
            </div>
            <div class="col-lg-4">
              <label for="surname" class="form-label">Uzvārds (-i)</label>
              <input v-model="user.surname" type="text" class="form-control" id="surname">
            </div>
            <div class="col-lg-4">
              <label for="person_code" class="form-label">Personas kods</label>
              <input v-model="user.person_code" type="text" class="form-control" id="person_code">
            </div>
            <div class="col-lg-4">
              <label for="birthdate" class="form-label">Dzimšanas datums</label>
              <input v-model="user.birthdate" type="date" class="form-control" id="birthdate">
            </div>
            <div class="col-lg-4">
              <label for="email" class="form-label">E-pasta adrese</label>
              <input v-model="user.email" type="email" class="form-control" id="email">
            </div>
            <div class="col-lg-4">
              <label for="phone" class="form-label">Telefona nr.</label>
              <input v-model="user.phone" type="text" class="form-control" id="phone">
            </div>
            <div class="col-lg-12">
              <label for="iban" class="form-label">Bankas konta nr.</label>
              <input v-model="user.iban_code" type="text" class="form-control" id="iban">
            </div>
            <div class="col-lg-4">
              <label for="password" class="form-label">Parole</label>
              <input v-model="user.password" type="password" class="form-control" id="password">
            </div>
            <div class="col-lg-4">
              <label for="password_confirmation" class="form-label">Paroles apstiprinājums</label>
              <input v-model="user.password_confirmation" type="password" class="form-control" id="password_confirmation">
            </div>
            <button type="submit" class="btn btn-primary col-4">Saglabāt</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!--  Pop-up modal for editing users -->
  <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <a class="navbar-brand" href="/"><img src="../../assets/logo-red.svg" height="45" /></a>
          <h5 class="modal-title mx-auto" id="exampleModalLabel">Lietotāja rediģēšana</h5>
          <button type="button" class="btn-close" data-dismiss="modal" @click="this.editModal.hide()" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="updateUser" class="row g-3 text-start">
            <div class="col-lg-4">
              <label for="name" class="form-label">Vārds (-i)</label>
              <input v-model="selectedUser.name" type="text" class="form-control" id="name">
            </div>
            <div class="col-lg-4">
              <label for="surname" class="form-label">Uzvārds (-i)</label>
              <input v-model="selectedUser.surname" type="text" class="form-control" id="surname">
            </div>
            <div class="col-lg-4">
              <label for="person_code" class="form-label">Personas kods</label>
              <input v-model="selectedUser.person_code" type="text" class="form-control" id="person_code">
            </div>
            <div class="col-lg-4">
              <label for="birthdate" class="form-label">Dzimšanas datums</label>
              <input v-model="selectedUser.birthdate" type="date" class="form-control" id="birthdate">
            </div>
            <div class="col-lg-4">
              <label for="email" class="form-label">E-pasta adrese</label>
              <input v-model="selectedUser.email" type="email" class="form-control" id="email">
            </div>
            <div class="col-lg-4">
              <label for="phone" class="form-label">Telefona nr.</label>
              <input v-model="selectedUser.phone" type="text" class="form-control" id="phone">
            </div>
            <div class="col-lg-6">
              <label for="iban" class="form-label">Bankas konta nr.</label>
              <input v-model="selectedUser.iban_code" type="text" class="form-control" id="iban">
            </div>
            <button type="submit" class="btn btn-primary col-6">Saglabāt</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</template>

<style scoped>
  p {
    margin: 0;
  }

  th, h5 {
    font-weight: bold;
  }
</style>

