<template>
  <div ref="chartdiv" style="width: 100%; height: 600px;"></div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import * as am4core from "@amcharts/amcharts4/core";
import * as am4maps from "@amcharts/amcharts4/maps";
import am4geodataWorldLow from "@amcharts/amcharts4-geodata/worldLow";

    const chartdiv = ref(null);
    let chart = null;

    // Инициализация карты
    const initMap = () => {
      chart = am4core.create(chartdiv.value, am4maps.MapChart);

      // Задаем географические данные
      chart.geodata = am4geodataWorldLow;

      // Создаем полигон для отображения стран
      const polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());
      polygonSeries.useGeodata = true;

      // Настройка внешнего вида полигонов
      const polygonTemplate = polygonSeries.mapPolygons.template;
      polygonTemplate.tooltipText = "{name}";
      polygonTemplate.fill = am4core.color("#67b7dc");

      // Изменяем цвет при наведении
      polygonTemplate.propertyFields.fill = "color";
      polygonTemplate.nonScalingStroke = true;
      polygonTemplate.strokeWidth = 0.5;
      polygonTemplate.stroke = am4core.color("#FFFFFF");

      // Добавляем зум-контроль
      chart.zoomControl = new am4maps.ZoomControl();
      chart.homeZoomLevel = 1;
      chart.homeGeoPoint = { longitude: 10, latitude: 55 }

      polygonTemplate.events.on("hit", (ev) => {
        console.log(ev.target.dataItem.dataContext.name)
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

<style scoped>
/* Стили для контейнера карты */
</style>
