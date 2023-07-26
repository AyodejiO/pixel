import { createApp } from "vue";
import { createPinia } from "pinia";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
  faCloud,
  faTemperatureLow,
  faTemperatureFull,
  faTemperatureHigh,
  faTemperatureThreeQuarters,
  faTint,
  faWind,
  faSun,
  faSnowflake,
  faCloudRain,
  faWater,
} from "@fortawesome/free-solid-svg-icons";
import { library } from "@fortawesome/fontawesome-svg-core";

import App from "./App.vue";
import router from "./router";

import "./assets/main.css";

library.add(
  faCloud,
  faTemperatureLow,
  faTint,
  faWind,
  faTemperatureFull,
  faTemperatureHigh,
  faTemperatureThreeQuarters,
  faSun,
  faSnowflake,
  faCloudRain,
  faWater
);

const app = createApp(App);
app.component("FontAwesomeIcon", FontAwesomeIcon);
app.use(createPinia());
app.use(router);

app.mount("#app");
