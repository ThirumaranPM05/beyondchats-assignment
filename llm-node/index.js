const express = require("express");
const enrichRoutes = require("./routes/enrich");

const app = express();
app.use(express.json());

// ✅ ROOT HEALTH CHECK (THIS IS WHAT YOU ARE MISSING)
app.get("/", (req, res) => {
  res.json({ status: "LLM service running" });
});

// API routes
app.use("/api", enrichRoutes);

const PORT = 4000;
app.listen(PORT, () => {
  console.log(`LLM service running on http://localhost:${PORT}`);
});
