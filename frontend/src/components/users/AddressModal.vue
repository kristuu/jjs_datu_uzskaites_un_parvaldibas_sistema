<script>
import VueGoogleAutocomplete from 'vue-google-autocomplete';
import axios from "@/services/axios";

export default {
  data() {
    return {
      addressModal: null,
      selectedUser: {},
      user: {
        address: {
          address_line1: '',
          address_line2: '',
          city: '',
          region: {
            name: '',
            country: {
              name: '',
            },
          },
          postal_code: '',
        },
      },
    };
  },
  props: {
    userData: Object,  // passed from parent view
  },
  components: {
    VueGoogleAutocomplete,
  },
  methods: {
    // Transferred from UserList.vue
    openAddressModal() {
      this.selectedUser = { ...this.userData };

      if (!this.addressModal) {
        this.$nextTick(() => {
          this.addressModal = new Modal(document.getElementById('editAddressModal'));
          this.addressModal.show();
        });
      } else {
        this.addressModal.show();
      }
    },
    // Transferred from UserList.vue
    getAddressData(addressData, placeResultData) {
      // this.user.address.country = placeResultData.country;
      const addressComponents = placeResultData.address_components;
      console.log(addressComponents);

      let route = '';
      let street_number = '';

      this.user = this.user || {};
      this.user.address = this.user.address || {};
      this.user.address.region = this.user.address.region || {};
      this.user.address.region.country = this.user.address.region.country || {};

      // Map the fields
      addressComponents.forEach(component => {
        const types = component.types;

        if (types.includes('country')) {
          this.user.address.region.country.name = component.long_name;
        }

        if (types.includes('administrative_area_level_1')) {
          this.user.address.region.name = component.long_name;
        }

        if (types.includes('locality')) {
          this.user.address.city = component.long_name;
        }

        if (types.includes('postal_code')) {
          this.user.address.postal_code = component.long_name;
        }

        if (types.includes('route')) {
          route = component.long_name;
        }

        if (types.includes('street_number')) {
          street_number = component.long_name;
        }

        if (types.includes('subpremise')) {
          this.user.address.address_line2 = component.long_name;
        } else {
          this.user.address.address_line2 = '';
        }
      });

      // Combine route and street_number
      this.user.address.address_line1 = route + ' ' + street_number;
    },
    async updateAddress(id) {
      try {
        console.log(this.user);
        await axios.put(`/address/${id}`, this.user);
        this.addressModal.hide();
        await this.fetchUsers();
      } catch (e) {
        this.errorList = e.response.data.errors;
        console.log(e);
      }
    },
  },
};
</script>

<template>

  <!--  Pop-up modal for editing address -->
  <div class="modal fade" id="editAddressModal" tabindex="-1" role="dialog" aria-labelledby="editAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <a class="navbar-brand" href="/frontend/public"><img src="../../assets/logo-red.svg" height="45" /></a>
          <h5 class="modal-title mx-auto" id="editAddressModalLabel">Adreses rediģēšana</h5>
          <button type="button" class="btn-close" data-dismiss="modal" @click="this.addressModal.hide()" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="updateAddress(user.person_code)" class="row g-3 text-start">
            <div class="col-12">
              <label for="googleplaces_address_code" class="form-label">Address</label>
              <vue-google-autocomplete
                  id="googleplaces_address_code"
                  classname="form-control"
                  placeholder="Sāciet rakstīt adresi..."
                  v-on:placechanged="getAddressData">
              </vue-google-autocomplete>
            </div>
            <div class="col-lg-6">
              <label for="address_line1" class="form-label">Adreses līnija 1 (iela, ēkas numurs)</label>
              <input :value="user.address.address_line1 ? user.address.address_line1 : ''"
                     @input="val => user.address.address_line1 = val.target.value"
                     type="text"
                     class="form-control"
                     id="address_line1" required>
            </div>
            <div class="col-lg-6">
              <label for="address_line2" class="form-label">Adreses līnija 2 (piem., dzīvokļa nr. [neobligāti])</label>
              <input
                  type="text"
                  class="form-control"
                  id="address_line2"
                  :value="user?.address?.address_line2 || ''"
                  @input="val => user.address.address_line2 = val.target.value"
              />
            </div>
            <div class="col-lg-4">
              <label for="city" class="form-label">Pilsēta</label>
              <input
                  type="text"
                  class="form-control"
                  id="city"
                  :value="user?.address?.city || ''"
                  @input="val => user.address.city = val.target.value"
                  required
              />
            </div>
            <div class="col-lg-4">
              <label for="region" class="form-label">Novads/reģions</label>
              <input
                  type="text"
                  class="form-control"
                  id="region_name"
                  :value="user?.address?.region?.name || ''"
                  @input="val => user.address.region.name = val.target.value"
                  required
              />
            </div>
            <div class="col-lg-4">
              <label for="postal_code" class="form-label">Pasta indekss</label>
              <input
                  type="text"
                  class="form-control"
                  id="postal_code"
                  :value="user?.address?.postal_code || ''"
                  @input="val => user.address.postal_code = val.target.value"
                  required
              />
            </div>
            <div class="col-lg-6">
              <label for="country" class="form-label">Valsts</label>
              <input
                  type="text"
                  class="form-control"
                  id="region_country_name"
                  :value="user?.address?.region?.country?.name || ''"
                  @input="val => user.address.region.country.name = val.target.value"
                  required
              />
            </div>
            <button type="submit" class="btn btn-primary col-6">Saglabāt</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</template>

<style>
/* styles here */
</style>