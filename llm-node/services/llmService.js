function enrichArticle(title, content) {
  return {
    summary: `Mock AI summary for: ${title}`,
    tags: ["AI", "Chatbots", "Customer Support"],
    sentiment: "positive",
  };
}

module.exports = {
  enrichArticle,
};
