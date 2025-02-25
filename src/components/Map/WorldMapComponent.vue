<template>
  <div class="col-12 col-md-12 text-center" style="min-height: 300px !important;">
      <div ref="chartdiv" style="width: 100%; height: 600px;"></div>
      <div class="title">Карта мира
        <q-icon
          class="text-green-8"
          style="font-size: 2rem"
          name="help"
          @click.prevent="showHelpMessage(1)"
        />
      </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import * as am4core from "@amcharts/amcharts4/core";
import * as am4maps from "@amcharts/amcharts4/maps";
import am4geodataWorldLow from "@amcharts/amcharts4-geodata/worldLow"
import {getCountryNamesRU} from "components/modules/maps/RU_names";
import geoData from "components/Map/word.json"

const chartdiv = ref(null);
let chart = null;

const emit = defineEmits(['onCountry'])
// Определение русской локали
const countryNamesRU = getCountryNamesRU()
// Функция для добавления русских названий в геоданные
function addRussianNamesToGeoData(geoData, countryNamesRU) {
  if (geoData && geoData.features) {
    geoData.features.forEach(feature => {
 //     debugger
      let isoCode
      if (feature.id) isoCode = feature.id
      else isoCode = feature.properties.id
      if (countryNamesRU[isoCode]) {
        feature.properties.nameRU = countryNamesRU[isoCode]; // Добавляем русское название
      } else {
        feature.properties.nameRU = feature.properties.name; // Если нет перевода, используем оригинальное имя
      }
    })
  }
}


    // Инициализация карты
    const initMap = () => {
      chart = am4core.create(chartdiv.value, am4maps.MapChart);
      // Создаем полигон для отображения стран
      const polygonSeries = chart.series.push(new am4maps.MapPolygonSeries())
      addRussianNamesToGeoData(geoData, countryNamesRU)
      polygonSeries.geodata = geoData
      polygonSeries.useGeodata = true
      polygonSeries.exclude = ["AQ"]

      // polygonSeries.events.on("datavalidated", function() {
      //   polygonSeries.mapPolygons.each(function(polygon, index) {
      //     polygon.fill = chart.colors.getIndex(index);
      //   })
      // })

      // Настройка внешнего вида полигонов
      const polygonTemplate = polygonSeries.mapPolygons.template;
      polygonTemplate.tooltipText = "{nameRU}"; // Используем русское название
      polygonTemplate.fill = am4core.color("#67b7dc");

      // Изменяем цвет при наведении
      polygonTemplate.propertyFields.fill = "color";
      polygonTemplate.nonScalingStroke = true;
      polygonTemplate.strokeWidth = 0.5;
      polygonTemplate.stroke = am4core.color("#FFFFFF");

      // Добавляем зум-контроль
      chart.zoomControl = new am4maps.ZoomControl();
      chart.homeZoomLevel = 1;
      chart.homeGeoPoint = { longitude: 0, latitude: 15 }

      // Настройка тултипов и надписей без использования am4core.locale
      chart.zoomControl.plusButton.tooltipText = "Увеличить";
      chart.zoomControl.minusButton.tooltipText = "Уменьшить";

      // Добавляем зум-контроль
      chart.zoomControl = new am4maps.ZoomControl();
      chart.zoomControl.plusButton.tooltipText = "Увеличить"; // Русский текст
      chart.zoomControl.minusButton.tooltipText = "Уменьшить"; // Русский текст

      /////события
      polygonTemplate.events.on("hit", (ev) => {
        // console.log(ev.target.dataItem.dataContext.name)
        // console.log(ev.target.dataItem.dataContext.nameRU)
        emit('onCountry',ev.target.dataItem.dataContext.name, ev.target.dataItem.dataContext.nameRU)
      })
      const hs = polygonTemplate.states.create("hover");
      hs.adapter.add("fill", function(fill) {
        return fill.brighten(-0.2);
      })
    }

    // Очищаем ресурсы при уничтожении компонента
    onBeforeUnmount(() => {
      if (chart) {
        chart.dispose()
      }
    })

    // Инициализируем карту при монтировании компонента
    onMounted(() => {
      if (chartdiv.value) {
        initMap()
      }
    })


</script>

<style lang="sass" scoped>
.title
  font-size: 1.5em
  font-weight: bold
  color: #2669a1
</style>
