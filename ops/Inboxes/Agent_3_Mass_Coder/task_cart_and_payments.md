# Task: Shopping Cart & Tpay (BLIK) Integration
**ID:** 2026_04_27_CART_PAYMENTS
**Assignee:** Agent_3_Mass_Coder

## Objective
Build the core shopping cart functionality and integrate Tpay (BLIK) payment system. Ensure fast, error-proof operations.

## Requirements

### Shopping Cart
1. **Cart Model**: Session-based cart with database persistence for logged users
2. **Cart Items**: Store product ID, quantity, options, customizations
3. **Cart Operations**: Add, remove, update quantity, clear cart
4. **Cart Calculations**: Subtotal, tax, shipping, total
5. **Cart Validation**: Stock checking, product availability, price validation
6. **Cart API**: REST endpoints for AJAX cart operations

### Tpay (BLIK) Integration
1. **Payment Service**: Tpay API integration with BLIK support
2. **Payment Flow**: Create transaction → Redirect to BLIK → Handle callbacks
3. **Error Handling**: Payment failures, timeouts, user cancellations
4. **Order Creation**: Convert cart to order on successful payment
5. **Webhook Handling**: Payment status updates from Tpay

## Technical Specifications
- Fast AJAX operations (under 200ms response time)
- Error-proof validation at every step
- UTF-8 support for Polish characters
- Session security and CSRF protection
- Payment logging and audit trail

## Validation
- PHP syntax check required
- Cart operations tested
- Payment flow tested with sandbox

## Finalization
- Push to `zibbie/nevro-shop-v2`
- Update report in `ops/Outboxes/Agent_3_Mass_Coder/report.md`