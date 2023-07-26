<script lang="ts">
import type { ApiResponse } from "./types";
export default {
  data: () => ({
    apiResponse: null as ApiResponse | null,
    usersWithWeather: [] as ApiResponse["users"],
    selectedUser: null as ApiResponse["users"][0] | null,
  }),

  created(): void {
    this.fetchData();
  },

  methods: {
    async fetchData() {
      const url = "http://localhost/";
      this.apiResponse = await (await fetch(url)).json();
      this.usersWithWeather = this.apiResponse?.users || [];
    },
    getWeatherIcon(weatherId: number) {
      switch (weatherId) {
        case 800:
          return "sun";
        case 801:
        case 802:
          return "cloud";
        case 803:
        case 804:
          return "cloud";
        default:
          return "cloud-rain";
      }
    },
    openModal(user: ApiResponse["users"][0]) {
      this.selectedUser = user;
    },
    closeModal() {
      this.selectedUser = null;
    },
  },
};
</script>

<template>
  <div v-if="!apiResponse">Pinging the api...</div>

  <div v-if="apiResponse">
    <div class="user-grid">
      <div
        v-for="(user, index) in usersWithWeather"
        :key="index"
        class="grid-cell"
        @click="openModal(user)"
      >
        <div class="user-info">
          <p><strong>Name:</strong> {{ user.user.name }}</p>
          <p>
            <strong>Email:</strong>
            <a href="mailto:{{ user.user.email }}">{{ user.user.email }}</a>
          </p>
          <p>
            <strong>Latitude:</strong>
            {{ Math.round(user.user.latitude * 10000) / 10000 }}
          </p>
          <p>
            <strong>Longitude:</strong>
            {{ Math.round(user.user.longitude * 10000) / 10000 }}
          </p>
        </div>
        <div class="weather-info">
          <h3>Weather Details</h3>
          <p>
            <font-awesome-icon
              :icon="getWeatherIcon(user.weather.weather[0].id)"
            />
            {{ user.weather.weather[0].description }}
          </p>
          <div class="weather-details">
            <div>
              <font-awesome-icon icon="temperature-low" />
              <span>{{ user.weather.main.temp }} K</span>
            </div>
            <div>
              <font-awesome-icon icon="tint" />
              <span>{{ user.weather.main.humidity }} %</span>
            </div>
            <div>
              <font-awesome-icon icon="wind" />
              <span>{{ user.weather.wind.speed }} m/s</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal -->
    <div v-if="selectedUser" class="modal" @click="closeModal">
      <div class="modal-content" @click.stop>
        <!-- Full user and weather details -->
        <div class="user-info">
          <h2>{{ selectedUser.user.name }}</h2>
          <p>
            Email:
            <a href="mailto:{{ selectedUser.user.email }}">{{
              selectedUser.user.email
            }}</a>
          </p>
          <p>
            <strong>Latitude:</strong>
            {{ Math.round(selectedUser.user.latitude * 10000) / 10000 }}
          </p>
          <p>
            <strong>Longitude:</strong>
            {{ Math.round(selectedUser.user.longitude * 10000) / 10000 }}
          </p>
        </div>
        <div class="weather-info">
          <h3>Weather Details</h3>
          <p>
            <font-awesome-icon
              :icon="getWeatherIcon(selectedUser.weather.weather[0].id)"
            />
            {{ selectedUser.weather.weather[0].description }}
          </p>
          <div class="weather-details">
            <div>
              <font-awesome-icon icon="temperature-low" />
              <span>{{ selectedUser.weather.main.temp }} K</span>
            </div>
            <div>
              <font-awesome-icon icon="tint" />
              <span>{{ selectedUser.weather.main.humidity }} %</span>
            </div>
            <div>
              <font-awesome-icon icon="wind" />
              <span>{{ selectedUser.weather.wind.speed }} m/s</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Modal -->
  </div>
</template>

<style>
.user-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
  margin: 0 auto;
  margin-top: 2rem;
  max-width: 90%;
  width: 100%;
}

.grid-cell {
  position: relative;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 9999;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  max-width: 90%;
  max-height: 90%;
  overflow-y: auto;
}

@media screen and (max-width: 768px) {
  .user-grid {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  }
}

@media screen and (max-width: 576px) {
  .user-grid {
    max-width: 100%;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  }
}
</style>
