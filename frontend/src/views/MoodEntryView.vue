<script setup>
import { computed, onMounted, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useMoodEntriesStore } from '../stores/moodEntries'

const route = useRoute()
const router = useRouter()
const moodEntries = useMoodEntriesStore()
const isEditing = computed(() => route.params.id !== 'new')
const form = reactive({
  date: new Date().toISOString().slice(0, 10),
  mood_score: 5,
  emotion: '',
  note: '',
})

onMounted(async () => {
  if (!isEditing.value) return

  const entry = await moodEntries.fetchEntry(route.params.id)
  form.date = entry.date
  form.mood_score = entry.mood_score
  form.emotion = entry.emotion
  form.note = entry.note || ''
})

async function submit() {
  if (isEditing.value) {
    await moodEntries.updateEntry(route.params.id, { ...form })
  } else {
    await moodEntries.createEntry({ ...form })
  }

  router.push({ name: 'mood-entries' })
}
</script>

<template>
  <section>
    <h1>{{ isEditing ? 'Edit Mood Entry' : 'New Mood Entry' }}</h1>

    <form class="form panel" @submit.prevent="submit">
      <label>
        Date
        <input v-model="form.date" type="date" required />
      </label>

      <label>
        Mood score
        <input v-model.number="form.mood_score" type="number" min="1" max="10" required />
      </label>

      <label>
        Emotion
        <input v-model="form.emotion" type="text" maxlength="80" required />
      </label>

      <label>
        Note
        <textarea v-model="form.note" rows="4" maxlength="2000" />
      </label>

      <p v-if="moodEntries.errors" class="error">{{ moodEntries.errors }}</p>

      <div class="form-actions">
        <button type="submit" :disabled="moodEntries.loading">
          {{ moodEntries.loading ? 'Saving...' : 'Save entry' }}
        </button>
        <RouterLink to="/mood-entries">Cancel</RouterLink>
      </div>
    </form>
  </section>
</template>
