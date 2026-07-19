# 1. Project Goal

## 1.1. 
Implement an AI-powered in-application assistant using Laravel 13 AI SDK.
The assistant must integrate naturally into the existing Housing Company application.
The implementation must follow Laravel AI SDK architecture and conventions.
The assistant is intended to help authenticated users work with the application rather than serve as a general-purpose chatbot.
The implementation must be modular and extensible, allowing future addition of new Agents, Tools, Structured Outputs, AI Providers, and other AI SDK components without major architectural changes.

## 1.2. 
Laravel AI SDK Usage Requirements:
The implementation should leverage Laravel AI SDK as much as reasonably possible.
Prefer Laravel AI SDK built-in features over custom implementations.
Reuse native SDK concepts whenever available, including:
- Agents
- Conversations
- Messages
- Tools
- Structured Outputs
- Middleware
- Streaming
Avoid reinventing functionality already provided by Laravel AI SDK.

## 1.3. 
General Architecture Requirements:
Follow Laravel 13 AI SDK best practices.
Follow existing Laravel and project coding conventions.
Prefer composition over inheritance.
Reuse existing Services instead of duplicating business logic.
Avoid large classes.
Keep every Tool focused on a single business operation.
Do not introduce breaking changes.
All new functionality must integrate naturally into the existing project architecture.
Business logic must remain inside Laravel Services whenever possible.
Agents must never contain business logic.
Agents are responsible only for:
- understanding user requests;
- selecting the appropriate Tool(s);
- composing the final AI response.
The chat must access application data only through AI SDK Tools.
Agents must never access Eloquent models or the database directly.
All authorization checks must reuse existing Laravel Policies.
If a design decision is ambiguous, choose the solution that best follows Laravel AI SDK conventions instead of inventing a custom architecture.

## 1.4.
Before implementing any changes:
- Analyze the entire repository architecture.
- Analyze existing authorization policies.
- Analyze middleware.
- Analyze models.
- Analyze services.
- Analyze web and API routes.
- Analyze Company, User, and Post domain logic.
- Analyze the current Alpine.js frontend architecture.
- Analyze the existing localization system.
- Analyze the existing role system (superadmin, admin, company_head, user).
Do not duplicate existing functionality.
Reuse existing services and authorization policies whenever possible.
Follow the existing coding style and project architecture.

## 1.5 
AI Provider Requirements:
The initial implementation must support the following AI providers:
- OpenAI
- Anthropic
- Gemini
- Ollama
Provider configuration must be managed through the application and editable via Chat Settings.
Switching providers must not require source code changes.
The implementation should make it easy to add new providers in the future.
