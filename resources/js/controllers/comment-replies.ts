import { Controller } from '@hotwired/stimulus';

export class CommentReplies extends Controller {
    connect() {
        this.element.addEventListener('click', e => {
            const expanded = this.element.getAttribute('aria-expanded') === 'false';
            this.element.setAttribute('aria-expanded', String(expanded));
            const controlled = this.element.closest('.comment')?.querySelector<HTMLElement>('.comment__replies');
            if (controlled) {
                controlled.hidden = ! expanded;
            }
            if (! expanded) {
                e.preventDefault();
            }
        });
    }
}