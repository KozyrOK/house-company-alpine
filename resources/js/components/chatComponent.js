export default function chatComponent(isSuperAdmin = false) {
    return {
        isSuperAdmin,
        modalOpen: false,
        historyOpen: false,
        settingsOpen: false,
        loading: false,
        conversation: null,
        conversations: [],
        messages: [],
        draft: '',
        settings: { provider: 'ollama', daily_request_limit: 5, warnings: [] },
        async startChat(agent = 'support') {
            this.loading = true;
            const response = await this.request('/chat/conversations', { method: 'POST', body: JSON.stringify({ agent }) });
            this.conversation = response;
            this.messages = [];
            this.modalOpen = true;
            this.loading = false;
        },
        async sendMessage() {
            if (!this.draft.trim() || !this.conversation) return;
            const text = this.draft;
            this.draft = '';
            this.messages.push({ role: 'user', content: text });
            this.loading = true;
            const response = await this.request(`/chat/conversations/${this.conversation.id}/messages`, { method: 'POST', body: JSON.stringify({ message: text }) });
            this.messages = response.conversation.messages;
            this.loading = false;
        },
        async loadHistory() {
            this.conversations = await this.request('/chat/conversations');
            this.historyOpen = true;
        },
        async openConversation(id) {
            this.conversation = await this.request(`/chat/conversations/${id}`);
            this.messages = this.conversation.messages;
            this.historyOpen = false;
            this.modalOpen = true;
        },
        async openSettings() {
            if (!this.isSuperAdmin) return;
            this.settings = await this.request('/chat/settings');
            this.settingsOpen = true;
        },
        async saveSettings() {
            this.settings = await this.request('/chat/settings', { method: 'PATCH', body: JSON.stringify(this.settings) });
            this.settingsOpen = false;
        },
        async request(url, options = {}) {
            const response = await fetch(url, {
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                ...options,
            });
            if (!response.ok) throw new Error('Chat request failed');
            return response.json();
        }
    };
}