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

const chartdiv = ref(null);
let chart = null;
// Определение русской локали
const countryNamesRU = getCountryNamesRU()
// Функция для добавления русских названий в геоданные
function addRussianNamesToGeoData(geoData, countryNamesRU) {
  debugger
  if (geoData && geoData.features) {
    geoData.features.forEach(feature => {
      const isoCode = feature.properties.id // Получаем код страны
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
      // Задаем географические данные
     // chart.geodata = am4geodataWorldLow;
      // Создаем полигон для отображения стран
      const polygonSeries = chart.series.push(new am4maps.MapPolygonSeries())
 //     polygonSeries.useGeodata = true
//      polygonSeries.exclude = ["AQ"]
      polygonSeries.geodataSource.url = "../src/components/Map/word.json"
      // Применяем обработку после загрузки данных
      polygonSeries.geodataSource.events.on("geodataupdated", () => {
        debugger
        const geoData = polygonSeries.geodataSource.data;
        addRussianNamesToGeoData(geoData, countryNamesRU);
      })

      // polygonSeries.events.on("datavalidated", function() {
      //
      //   polygonSeries.mapPolygons.each(function(polygon, index) {
      //     polygon.fill = chart.colors.getIndex(index);
      //   })
      // })

      // Настройка внешнего вида полигонов
      const polygonTemplate = polygonSeries.mapPolygons.template;
      polygonTemplate.tooltipText = "{nameRU}"; // Используем русское название
 //     polygonTemplate.tooltipText = "{name}";
      polygonTemplate.fill = am4core.color("#67b7dc");



      // Изменяем цвет при наведении
      polygonTemplate.propertyFields.fill = "color";
      polygonTemplate.nonScalingStroke = true;
      polygonTemplate.strokeWidth = 0.5;
      polygonTemplate.stroke = am4core.color("#FFFFFF");

      // Добавляем зум-контроль
      chart.zoomControl = new am4maps.ZoomControl();
      chart.homeZoomLevel = 1;
      chart.homeGeoPoint = { longitude: 0, latitude: 0 }

      // Настройка тултипов и надписей без использования am4core.locale
      chart.zoomControl.plusButton.tooltipText = "Увеличить";
      chart.zoomControl.minusButton.tooltipText = "Уменьшить";

      // Добавляем зум-контроль
      chart.zoomControl = new am4maps.ZoomControl();
      chart.zoomControl.plusButton.tooltipText = "Увеличить"; // Русский текст
      chart.zoomControl.minusButton.tooltipText = "Уменьшить"; // Русский текст

      /////события
      polygonTemplate.events.on("hit", (ev) => {
        console.log(ev.target.dataItem.dataContext.name)
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
