<script setup>
import { Bar } from 'vue-chartjs';
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from 'chart.js';
import { computed } from 'vue';

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
);

const props = defineProps(['data']);

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false

};

const chartData = computed(() => {
  if (!props.data) return null;
  return {
    labels: Object.keys(props.data),
    datasets: [
      {
        data: Object.values(props.data),
        label: 'Hours',
        backgroundColor: '#B16CB5',
      },
    ],
  };
});
</script>

<template>
  <Bar
    id="my-chart-id"
    v-if="chartData"
    :options="chartOptions"
    :data="chartData"
  />
</template>
