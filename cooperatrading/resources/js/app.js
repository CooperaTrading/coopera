import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('countUpStats', () => ({
    values: [0, 0, 0, 0],
    targets: [6, 35, 24, 15],
    frame: null,
    observer: null,
    init() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            this.values = [...this.targets];
            return;
        }

        this.observer = new IntersectionObserver(([entry]) => {
            if (!entry.isIntersecting) return;

            this.observer.disconnect();
            this.animate();
        }, { threshold: 0.25 });

        this.observer.observe(this.$el);
    },
    animate() {
        const duration = 1400;
        const startedAt = performance.now();

        const update = (now) => {
            const progress = Math.min((now - startedAt) / duration, 1);
            const easedProgress = 1 - Math.pow(1 - progress, 3);

            this.values = this.targets.map((target) => Math.round(target * easedProgress));

            if (progress < 1) {
                this.frame = requestAnimationFrame(update);
            }
        };

        this.frame = requestAnimationFrame(update);
    },
    destroy() {
        this.observer?.disconnect();

        if (this.frame) cancelAnimationFrame(this.frame);
    },
}));

Alpine.start();
