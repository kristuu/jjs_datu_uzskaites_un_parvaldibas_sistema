<script>
import axios from '@/services/axios';

export default {
  data() {
    return {
      users: [],
      currentPage: 1,
      totalPages: 0,
    };
  },
  methods: {
    async fetchUsers() {
      try {
        const response = await axios.get(`/users?page=${this.currentPage}`);

        this.users = response.data;
      } catch (e) {
        console.error('Kļūda, atgūstot lietotāju sarakstu: ', e);
      }
    },
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
    <div class="table-responsive small">
      <table class="table table-striped table-sm">
        <thead>
          <th scope="col">Personas kods</th>
          <th scope="col">Vārds (-i)</th>
          <th scope="col">Uzvārds (-i)</th>
          <th scope="col">Dzimšanas datums</th>
          <th scope="col">E-pasts</th>
          <th scope="col">Tel. nr.</th>
          <th scope="col">GPlaces adreses ID</th>
          <th scope="col">Bankas konta nr.</th>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.person_code">
            <td>{{ user.person_code }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.surname }}</td>
            <td>{{ user.birthdate }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.phone }}</td>
            <td>{{ user.googleplaces_address_code }}</td>
            <td>{{ user.iban_code }}</td>
          </tr>
        </tbody>
      </table>

<!--      Pagination-->
      <nav aria-label="Tabulas navigācija">
        <ul class="pagination">
          <li class="page-item" :class="{disabled: currentPage === 1}" @click="currentPage > 1 ? currentPage-- : null">
            <a class="page-link" href="#">
              Iepriekšējā
            </a>
          </li>
          <li class="page-item" :class="{disabled: currentPage === totalPages}" @click="currentPage < totalPages ? currentPage++ : null">
            <a class="page-link" href="#">
              Nākamā
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

