const express = require("express");
const router = express.Router();
const llmService = require("../services/llmService");

router.post("/enrich", async (req, res) => {
  const { title, content } = req.body;

  if (!title || !content) {
    return res.status(400).json({
      success: false,
      message: "title and content are required",
    });
  }

  const enriched = llmService.enrichArticle(title, content);

  return res.json({
    success: true,
    data: enriched,
  });
});

module.exports = router;
