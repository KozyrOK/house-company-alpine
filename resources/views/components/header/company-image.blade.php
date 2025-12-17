<div x-data="companyImageComponent" x-init="start()" class="company-image-header-container">
    <img
        x-ref="logo"
        :src="imageSrc"
        alt="Company Logo"
        class="company-image-header"
    @@error="onImageError"
    loading="lazy">
</div>
