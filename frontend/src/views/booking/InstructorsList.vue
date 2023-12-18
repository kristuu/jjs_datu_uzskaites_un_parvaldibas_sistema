<script>
import axios from '@/services/axios';
import { Modal } from 'bootstrap';
export default {
  name: "InstructorsList",
  data() {
    return {
      instructors: [],
      selectedInstructor: {},
      selectedTime: {},
      availabilitiesByDate: {},
      bookModal: null,
      errorList: {},
      isLoading: null,
      smallestDate: null,
    }
  },
  methods: {
    async fetchInstructors() {
      this.isLoading = true;
      try {
        const response = await axios.get(`/instructors`);

        this.instructors = response.data.message;
        this.isLoading = false;
      } catch (e) {
        if (e.response) {
          if (e.response.status === 422) {
            this.errorList = e.response.data.errors;
            console.log(this.errorList);
          }
        } else if (e.request) {
          console.log(e.request);
        } else {
          console.log('Unrecognized error: ', e.message);
        }
        this.isLoading = false;
      }
    },
    openBookingModal(instructor) {
      this.selectedInstructor = { ...instructor };
      console.log(this.selectedInstructor);
      if (this.selectedInstructor.availability.length) {
        this.availabilitiesByDate = this.selectedInstructor.availability.reduce((grouped, availability) => {
          let date = availability.start_time.split('T')[0]; // get only the date portion from datetime string
          (grouped[date] = grouped[date] || []).push(availability); // push into the array under the corresponding date property

          // Sort the availabilities for this date by start_time
          grouped[date].sort((a, b) => {
            return new Date(a.start_time) - new Date(b.start_time);
          });

          return grouped;
        }, {});
        this.smallestDate = Object.keys(this.availabilitiesByDate).reduce((a, b) => a < b ? a : b);
      }
      if (!this.bookModal) {
        this.$nextTick(() => {
          this.bookModal = new Modal(document.getElementById('bookingModal'));
          this.bookModal.show();
        });
      } else {
        this.bookModal.show();
      }
    },
    async attemptBooking() {
      try {
        var checkedValue = document.querySelector("input[name=availabilityTime]:checked").value;
        await axios.post('/book', { id: checkedValue });
        this.resetModal();
        this.bookModal.hide();
        this.fetchInstructors();
      } catch (e) {
        if (e.response) {
          if (e.response.status === 422) {
            this.errorList = e.response.data.errors;
            console.log(this.errorList);
          }
        } else if (e.request) {
          console.log(e.request);
        } else {
          console.log('Unrecognized error: ', e.message);
        }
      }
    },
    resetModal() {
      this.selectedInstructor = {};
      this.selectedTime = {};
      this.availabilitiesByDate = {};
      this.smallestDate = null;
    },
    formatTime(datetime) {
      let date = new Date(datetime);
      let hours = date.getUTCHours();
      let minutes = ("0" + date.getMinutes()).slice(-2); // add leading zero if needed
      return `${hours}:${minutes}`;
    }
  },
  created() {
    this.fetchInstructors();
  },
  watch: {
    currentPage() {
      this.fetchInstructors();
    }
  }
}
</script>

<template>
  <div class="container-xl">
    <h2 class="fw-bold mb-5">Treneru saraksts</h2>
    <div class="container-fluid">
      <div class="row g-3">
        <div class="col-12" v-if="this.isLoading">
          <h3>Notiek ielāde...</h3>
        </div>
        <div class="col-md-4" v-else v-for="(instructor) in this.instructors" :key="instructor.id">
          <div class="card text-start">
            <div class="card-body">
              <h5 class="card-title">{{ instructor.user.name + " " + instructor.user.surname }}</h5>
              <h6 class="card-subtitle mb-2 text-body-secondary">{{ instructor.certificate.category.name }}</h6>
              <div style="height: 100px;">
                <p class="card-text">{{ instructor.short_description }}</p>
              </div>
              <a class="btn btn-primary" @click="openBookingModal(instructor)">Pieteikt rezervāciju</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!--  Pop-up modal for booking reservation (detailed instructor page) -->
  <div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <a class="navbar-brand" href="/"><img src="../../assets/logo-red.svg" height="45" /></a>
          <h5 class="modal-title mx-auto" id="bookingModalLabel">Trenera profils</h5>
          <button type="button" class="btn-close" data-dismiss="modal" @click="this.bookModal.hide()" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <h5>{{ this.selectedInstructor?.user?.name + " " + this.selectedInstructor?.user?.surname }}</h5>
              <h6 class="text-body-secondary">{{ this.selectedInstructor?.certificate?.category?.name }}</h6>
              <p>{{ this.selectedInstructor?.description }}</p>
            </div>
            <div class="col-12">
              <div id="availabilityCarousel" class="carousel carousel-dark slide" data-bs-ride="carousel"
              v-if="this.selectedInstructor?.availability?.length">
                <h6 class="text-start fw-bold">Pieejamība:</h6>
                <div class="carousel-inner">
                  <div class="carousel-item" v-for="(availabilities, date) in this.availabilitiesByDate"
                       :class="{ 'active': date === this.smallestDate }" :key="date">
                    <h3 class="mb-3">{{ date }}</h3>
                    <div class="row g-3 mb-5">
                      <div class="col" v-for="(availability, index) in availabilities" :key="index">
                        <div>
                          <input type="radio" class="btn-check"
                                 :value="availability.id"
                                 v-model="this.selectedTime"
                                 :id="`time-${date}-${index}`"
                                 name="availabilityTime">
                          <label :for="`time-${date}-${index}`" class="btn btn-sm btn-primary">{{ formatTime(availability.start_time) + " - " + formatTime(availability.end_time) }}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <button class="carousel-control-prev" type="button"
                        data-bs-target="#availabilityCarousel" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Iepriekšējā diena</span>
                </button>
                <button class="carousel-control-next" type="button"
                        data-bs-target="#availabilityCarousel" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Nākamā diena</span>
                </button>
              </div>
              <div v-else>
                <h6>Diemžēl pašlaik nav pieejamu rezervāciju</h6>
              </div>
            </div>
          </div>
          <form @submit.prevent="attemptBooking" class="row g-3 text-start" v-if="this.selectedInstructor?.availability?.length">
            <button type="submit" class="btn btn-primary col-6 m-auto">Apstiprināt rezervācijas pieteikšanu</button>
            <p class="mt-5 px-3"><strong>UZMANĪBAI!</strong> Pārliecinieties, ka Jūsu kontaktinformācija ir aktualizēta.
            Pēc rezervācijas pieteikšanas treneris var Jums zvanīt, lai precizētu informāciju.</p>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>