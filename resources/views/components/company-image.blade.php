<div x-data="companyImage" x-init="init()" class="company-image-container">
    <img :src="imageSrc"
         alt="Company Logo"
         class="company-image"
    @@error="onImageError">
</div>
