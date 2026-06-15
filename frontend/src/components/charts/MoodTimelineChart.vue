<script setup>
import * as d3 from 'd3'
import { onMounted, ref, watch } from 'vue'

const props = defineProps({
  entries: {
    type: Array,
    default: () => [],
  },
})

const svgRef = ref(null)

function renderChart() {
  const svg = d3.select(svgRef.value)
  svg.selectAll('*').remove()

  const data = props.entries
    .filter((entry) => entry.date && entry.mood_score)
    .map((entry) => ({
      date: new Date(`${entry.date}T00:00:00`),
      mood: Number(entry.mood_score),
    }))
    .sort((a, b) => a.date - b.date)

  if (!data.length) return

  const width = 720
  const height = 280
  const margin = { top: 20, right: 20, bottom: 42, left: 42 }
  const innerWidth = width - margin.left - margin.right
  const innerHeight = height - margin.top - margin.bottom
  const dates = d3.extent(data, (entry) => entry.date)

  if (dates[0].getTime() === dates[1].getTime()) {
    dates[0] = d3.timeDay.offset(dates[0], -1)
    dates[1] = d3.timeDay.offset(dates[1], 1)
  }

  const x = d3.scaleTime().domain(dates).range([0, innerWidth])
  const y = d3.scaleLinear().domain([1, 10]).range([innerHeight, 0])
  const chart = svg
    .attr('viewBox', `0 0 ${width} ${height}`)
    .append('g')
    .attr('transform', `translate(${margin.left},${margin.top})`)

  chart
    .append('g')
    .attr('transform', `translate(0,${innerHeight})`)
    .call(d3.axisBottom(x).ticks(Math.min(data.length, 5)).tickSizeOuter(0))

  chart.append('g').call(d3.axisLeft(y).ticks(5))

  chart
    .append('path')
    .datum(data)
    .attr('fill', 'none')
    .attr('stroke', '#2563eb')
    .attr('stroke-width', 2)
    .attr(
      'd',
      d3
        .line()
        .x((entry) => x(entry.date))
        .y((entry) => y(entry.mood)),
    )

  chart
    .selectAll('circle')
    .data(data)
    .join('circle')
    .attr('cx', (entry) => x(entry.date))
    .attr('cy', (entry) => y(entry.mood))
    .attr('r', 4)
    .attr('fill', '#2563eb')
}

onMounted(renderChart)
watch(() => props.entries, renderChart, { deep: true })
</script>

<template>
  <svg ref="svgRef" class="chart-svg" role="img" aria-label="Mood timeline chart" />
</template>
