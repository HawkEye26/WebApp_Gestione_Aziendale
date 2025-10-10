<template>
  <div>
    <h2>Gestione Aziende</h2>
    <button @click="fetchCompanies">Mostra tutte le aziende</button>
    <button @click="showCreateForm = !showCreateForm">Crea nuova azienda</button>

    <!-- Form creazione azienda -->
    <div v-if="showCreateForm">
      <h3>Nuova Azienda</h3>
      <form @submit.prevent="createCompany">
        <input v-model="form.company_name" placeholder="Nome azienda" required />
        <input v-model="form.address" placeholder="Indirizzo" required />
        <input v-model="form.zip_code" placeholder="CAP" required />
        <input v-model="form.city" placeholder="Città" required />
        <input v-model="form.province" placeholder="Provincia" required />
        <input v-model="form.region" placeholder="Regione" required />
        <input v-model="form.email" placeholder="Email" required />
        <button type="submit">Salva</button>
      </form>
    </div>

    <!-- Elenco aziende -->
    <ul>
      <li v-for="company in companies" :key="company.id">
        {{ company.company_name }} - {{ company.email }}
        <button @click="editCompany(company)">Modifica</button>
        <button @click="deleteCompany(company.id)">Elimina</button>
      </li>
    </ul>

    <!-- Form modifica azienda -->
    <div v-if="showEditForm">
      <h3>Modifica Azienda</h3>
      <form @submit.prevent="updateCompany">
        <input v-model="form.company_name" placeholder="Nome azienda" required />
        <input v-model="form.address" placeholder="Indirizzo" required />
        <input v-model="form.zip_code" placeholder="CAP" required />
        <input v-model="form.city" placeholder="Città" required />
        <input v-model="form.province" placeholder="Provincia" required />
        <input v-model="form.region" placeholder="Regione" required />
        <input v-model="form.email" placeholder="Email" required />
        <button type="submit">Aggiorna</button>
        <button @click="cancelEdit">Annulla</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const companies = ref([])
const showCreateForm = ref(false)
const showEditForm = ref(false)
const form = ref({
  company_name: '',
  address: '',
  zip_code: '',
  city: '',
  province: '',
  region: '',
  email: '',
})
const editId = ref(null)

async function fetchCompanies() {
  const res = await fetch('/api/companies')
  companies.value = await res.json()
}

async function createCompany() {
  await fetch('/api/companies', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(form.value),
  })
  showCreateForm.value = false
  resetForm()
  fetchCompanies()
}

function editCompany(company) {
  showEditForm.value = true
  editId.value = company.id
  form.value = { ...company }
}

async function updateCompany() {
  await fetch(`/api/companies/${editId.value}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(form.value),
  })
  showEditForm.value = false
  resetForm()
  fetchCompanies()
}

async function deleteCompany(id) {
  await fetch(`/api/companies/${id}`, { method: 'DELETE' })
  fetchCompanies()
}

function cancelEdit() {
  showEditForm.value = false
  resetForm()
}

function resetForm() {
  form.value = {
    company_name: '',
    address: '',
    zip_code: '',
    city: '',
    province: '',
    region: '',
    email: '',
  }
}
</script>