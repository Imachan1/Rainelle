<script setup>
import * as d3 from 'd3'
import { onMounted, ref, watch } from 'vue'

const props = defineProps({
  items: {
    type: Array,
    default: () => [],
  },
})

const svgRef = ref(null)

function renderChart() {
  const svg = d3.select(svgRef.value)
  svg.selectAll('*').remove()

  const data = props.items.map(([condition, average]) => ({
    condition,
    average: Number(average),
  }))

  if (!data.length) return

  const width = 720
  const height = 300
  const margin = { top: 20, right: 20, bottom: 70, left: 42 }
  const innerWidth = width - margin.left - margin.right
  const innerHeight = height - margin.top - margin.bottom
  const x = d3
    .scaleBand()
    .domain(data.map((entry) => entry.condition))
    .range([0, innerWidth])
    .padding(0.24)
  const y = d3.scaleLinear().domain([0, 10]).range([innerHeight, 0])
  const chart = svg
    .attr('viewBox', `0 0 ${width} ${height}`)
    .append('g')
    .attr('transform', `translate(${margin.left},${margin.top})`)

  chart
    .append('g')
    .attr('transform', `translate(0,${innerHeight})`)
    .call(d3.axisBottom(x).tickSizeOuter(0))
    .selectAll('text')
    .attr('transform', 'rotate(-25)')
    .attr('text-anchor', 'end')

  chart.append('g').call(d3.axisLeft(y).ticks(5))

  chart
    .selectAll('rect')
    .data(data)
    .join('rect')
    .attr('x', (entry) => x(entry.condition))
    .attr('y', (entry) => y(entry.average))
    .attr('width', x.bandwidth())
    .attr('height', (entry) => innerHeight - y(entry.average))
    .attr('fill', '#16a34a')
    .attr('rx', 4)

  chart
    .selectAll('.bar-label')
    .data(data)
    .join('text')
    .attr('class', 'bar-label')
    .attr('x', (entry) => x(entry.condition) + x.bandwidth() / 2)
    .attr('y', (entry) => y(entry.average) - 8)
    .attr('text-anchor', 'middle')
    .text((entry) => entry.average.toFixed(1))
}

onMounted(renderChart)
watch(() => props.items, renderChart, { deep: true })
</script>

<template>
  <svg ref="svgRef" class="chart-svg" role="img" aria-label="Mood by weather chart" />
</template>
