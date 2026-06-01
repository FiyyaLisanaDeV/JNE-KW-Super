import { Client } from "@modelcontextprotocol/sdk/client/index.js";
import { StdioClientTransport } from "@modelcontextprotocol/sdk/client/stdio.js";

async function main() {
  const transport = new StdioClientTransport({
    command: "npx",
    args: ["chrome-devtools-mcp@latest", "-y", "--no-sandbox", "--disable-setuid-sandbox", "--disable-dev-shm-usage", "--disable-gpu", "--headless"]
  });

  const client = new Client(
    { name: "mcp-debugger", version: "1.0.0" },
    { capabilities: {} }
  );

  await client.connect(transport);
  
  console.log("Connected to Chrome DevTools MCP. Navigating to Admin Dashboard...");
  
  await client.callTool({
    name: "navigate_page",
    arguments: { url: "https://kurir.fiyya.cloud/admin" }
  });
  
  console.log("Navigated. Waiting 4 seconds for UI and JS to load...");
  await new Promise(r => setTimeout(r, 4000));
  
  const consoleMsgs = await client.callTool({
    name: "list_console_messages",
    arguments: {}
  });
  console.log("\n--- CONSOLE MESSAGES ---");
  console.log(consoleMsgs.content?.[0]?.text || "No console messages found.");
  
  try {
      const networkMsgs = await client.callTool({
        name: "list_network_requests",
        arguments: {}
      });
      console.log("\n--- NETWORK REQUESTS ---");
      const netText = networkMsgs.content?.[0]?.text || "";
      console.log(netText.substring(0, 1500) + (netText.length > 1500 ? "... (truncated)" : ""));
  } catch (err) {
      console.log("\nCould not fetch network requests:", err.message);
  }
  
  process.exit(0);
}

main().catch(console.error);
