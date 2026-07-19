# 2. Chat frontend

## 2.1. 
The initial chat page already exists:
resources/views/pages/chat.blade.php
Reuse this template instead of creating a new page.
All frontend functionality must be implemented using Alpine.js and integrated into the existing UI style of the application.
The chat UI must support both dark and light themes and reuse existing Tailwind CSS styles whenever possible.

## 2.2 
Chat frontend for user (with superadmin role): three buttons - “Start chat”, “Chat settings”, “Chat History”.
“Chat settings” - Only available for superadmin.
Available settings:
AI Provider
Daily Request Limit
The settings must be stored in the database.
Changing settings must not require modifying .env manually.
The application should allow switching providers without source code changes.
“Chat History” - chat history.
Chat frontend for users (with other roles except superadmin): two buttons - “Start chat” and “Chat History”. 

## 2.3 
Clicking the "Start chat" button opens a pop-up chat window (via Alpine.js). The chat window appears as a chat window (there's a cross in the upper right corner to close the window). On the left are AI-generated chat messages, and on the right are user-written chat messages.
Start Chat:
- creates a new conversation
- conversation starts with no messages
Chat History:
- displays previous conversations
- user can reopen any previous conversation
- messages are loaded from conversation history
Each conversation contains many messages.
