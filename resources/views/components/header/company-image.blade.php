<div x-data="companyImage" x-init="init()" class="company-image-container">
    <img
        x-ref="logo"
        :src="imageSrc"
        alt="Company Logo"
        class="company-image"
    @@error="onImageError"
    loading="lazy">
</div>
