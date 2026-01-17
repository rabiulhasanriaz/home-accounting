<template>
    <div class="container p-3 mb-2 bg-info text-dark">
        <h1>Account</h1>

        <div class="card text-center">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Monthly Expenses</span>
                <span class="fw-bold">{{ clock }}</span>
            </div>

            <div class="card-body">
                <h5 class="card-title">Month of {{ monthTitle }}</h5>
            </div>

            <div class="card-footer text-muted">
                {{ daysLeft }} Days to go
            </div>
        </div>

        <div v-if="successMsg" class="alert alert-success mt-3">
            {{ successMsg }}
        </div>

        <form class="mt-3" @submit.prevent="submit">
            <select v-model.number="form.spender" class="me-2">
                <option :value="0">Select User</option>
                <option :value="1">Riaz</option>
                <option :value="2">Tonni</option>
            </select>

            <select v-model.number="form.purpose" class="me-2">
                <option :value="0">Select Purpose</option>
                <option :value="1">Self</option>
                <option :value="2">Family Maintenance</option>
                <option :value="3">Other</option>
            </select>

            <input v-model="form.date" type="date" required class="me-2" />
            <input v-model.number="form.amount" type="number" step="0.01" placeholder="Amount" required class="me-2" />
            <input v-model="form.remarks" type="text" placeholder="Remarks" class="me-2" />

            <button class="btn btn-primary" type="submit">Submit</button>
        </form>

        <div class="box-body mt-4">
            <table id="example" class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Purpose</th>
                    <th>Date</th>
                    <th>Remarks</th>
                    <th>Entry date</th>
                    <th style="text-align:right;">Amount</th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="row in rows" :key="row.id">
                    <td>{{ spenderLabel(row.spender) }}</td>
                    <td>{{ purposeLabel(row.purpose) }}</td>
                    <td>{{ row.date }}</td>
                    <td>{{ row.remarks }}</td>
                    <td>{{ formatEntryDate(row.created_at) }}</td>
                    <td style="text-align:right;"><strong>{{ formatMoney(row.amount) }} €</strong></td>
                </tr>
                </tbody>

                <tfoot>
                <tr>
                    <th colspan="4" :style="{ textAlign:'right', color: riazTotal > tonniTotal ? 'red' : 'green' }">
                        Riaz: {{ formatMoney(riazTotal) }} €
                    </th>
                    <th :style="{ textAlign:'right', color: riazTotal < tonniTotal ? 'red' : 'green' }">
                        Tonni: {{ formatMoney(tonniTotal) }} €
                    </th>
                    <th style="text-align:right;">
                        Total: {{ formatMoney(total) }} €
                    </th>
                </tr>
                </tfoot>
            </table>
        </div>

        <footer class="mt-4">
            <p>Author: Rabiul Hasan, Sumiya Islam Tonni</p>
            <p><a href="mailto:rabiulhasanriaz@gmail.com">Email Me</a></p>
        </footer>
    </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref, nextTick } from "vue";
import axios from "axios";

// DataTables (jQuery + plugin)
// import $ from "jquery";
// import "datatables.net-bs5";
// import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";
import $ from "jquery";
window.$ = window.jQuery = jQuery;;

import "datatables.net-bs5";
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";


// If you also want Bootstrap styles in your app, install & import bootstrap globally in main.js
// import "bootstrap/dist/css/bootstrap.min.css";
// import "bootstrap/dist/js/bootstrap.bundle.min.js";

const rows = ref([]);         // replaces $data from Blade
const successMsg = ref("");

const form = ref({
    spender: 0,
    purpose: 0,
    date: "",
    amount: null,
    remarks: "",
});

// Clock
const clock = ref("");
let clockTimer = null;

function updateClock() {
    const now = new Date();
    const pad = (n) => String(n).padStart(2, "0");
    clock.value = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
}

// Month title like "January, 2026"
const monthTitle = computed(() => {
    const now = new Date();
    return now.toLocaleString(undefined, { month: "long", year: "numeric" });
});

// days left in current month
const daysLeft = computed(() => {
    const now = new Date();
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0).getDate();
    return lastDay - now.getDate();
});

// Totals (replaces @php accumulation)
const total = computed(() => rows.value.reduce((sum, r) => sum + Number(r.amount || 0), 0));
const riazTotal = computed(() => rows.value.filter(r => r.spender === 1).reduce((s, r) => s + Number(r.amount || 0), 0));
const tonniTotal = computed(() => rows.value.filter(r => r.spender === 2).reduce((s, r) => s + Number(r.amount || 0), 0));

function spenderLabel(v) {
    return v === 1 ? "Riaz" : "Tonni";
}
function purposeLabel(v) {
    if (v === 1) return "Self";
    if (v === 2) return "Family Maintenance";
    return "Other";
}
function formatMoney(n) {
    return Number(n || 0).toFixed(2);
}
function formatEntryDate(iso) {
    // expects an ISO string from API, e.g. "2026-01-08T12:34:56.000000Z"
    const d = new Date(iso);
    return d.toLocaleString(undefined, {
        weekday: "short",
        month: "short",
        day: "numeric",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
}

// DataTables instance
let dt = null;

async function loadRows() {
    // Example API endpoint. You must create this in Laravel.
    // GET /api/expenses -> returns array
    const res = await axios.get("/api/expenses");
    rows.value = res.data;
}

async function submit() {
    // POST /api/expenses
    await axios.post("/api/expenses", form.value);

    successMsg.value = "Saved successfully!";
    form.value = { spender: 0, purpose: 0, date: "", amount: null, remarks: "" };

    // reload data
    await loadRows();

    // refresh datatable view (simple approach: destroy & re-init)
    await nextTick();
    if (dt) dt.destroy();
    dt = $("#example").DataTable();
}

onMounted(async () => {
    updateClock();
    clockTimer = setInterval(updateClock, 1000);

    await loadRows();
    await nextTick();
    dt = $("#example").DataTable();
});

onBeforeUnmount(() => {
    if (clockTimer) clearInterval(clockTimer);
    if (dt) dt.destroy();
});
</script>
