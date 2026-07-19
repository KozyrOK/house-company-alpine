# 4. The architecture of the main entities should look like this:

## 4.1. Agents. Agents must not contain business logic.
Business logic must remain inside existing Laravel Services.
Agents are responsible only for:
- interpreting user requests
- deciding which Tool to call
- composing the final AI response
Current implementation must contain two agents:
### 4.1.1 SupportChatAgent
Purpose: General conversational assistant for authenticated users.
Responsibilities:
- answer questions about the application
- use AI SDK Tools whenever application data is required
- maintain conversation context
- respect authorization rules
- provide structured responses when applicable
Available for all authenticated users.
### 4.1.2. AdminAssistantAgent
Purpose: Administrative assistant.
Responsibilities:
- answer administrative questions
- provide information available only to superadmin
- access additional administrative Tools
- help configure AI Chat
- provide diagnostics information
Available only for users with the superadmin role.
The architecture must allow adding new Agents in the future without modifying existing code.
## 4.2. Tools. 
Tools must be small and focused.
One Tool must perform exactly one business operation.
Required Tools:
- GetUserCompaniesTool
- GetCompanyDetailsTool
- SearchPostsTool
- GetPostDetailsTool
- SearchUsersTool
Every Tool must:
- use existing Laravel Services whenever possible
- respect existing Policies
- never bypass authorization
- never access unauthorized resources
Agent must never query Eloquent models directly.
All application data must be obtained only through Tools.
Every Tool must return structured domain data.
Formatting for display must be performed by the Agent rather than inside the Tool.

## 4.3 Structured Output. Implement Structured Output for common user intents.
Examples:
User:
"Show my companies"
↓
{
    "intent": "list_companies"
}
--------------------------------
User:
"Show latest posts"
↓
{
    "intent":"search_posts",
    "status":"active"
}
--------------------------------
User:
"Who is administrator?"
↓
{
    "intent":"search_users",
    "role":"admin"
}

Structured Output must be used internally by the application.
The frontend should still display natural language responses generated from the structured result.

## 4.4 Middleware - TokenUsageMiddleware.php
Responsibilities:
- count requests
- count input/output tokens
- deny requests when limit is exceeded
Current limit: 5 requests per user.
The implementation must allow replacing this limit by configuration in the future.
## 4.5. Messages/Conversation
Conversation model
Conversation
represents one independent dialog.
Messages
represent messages inside a conversation.
Relationship:
Conversation
1 -----> many Messages
--------------------------------
Start Chat
creates a new Conversation.
The Conversation initially contains zero Messages.
--------------------------------
Each user message creates a new Message.
Each AI response creates another Message.
--------------------------------
Chat History
shows all previous Conversations.
Opening a previous Conversation loads all Messages belonging to that Conversation.
--------------------------------
The user can:
- create unlimited Conversations
- continue previous Conversations
- delete Conversations (optional future feature)
